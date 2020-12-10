<?php
/**
 * Plugin Name: WPBakery Page Builder Content Blocks
 * Plugin URI: http://github.com/wolfthemes/wolf-vc-content-block
 * Description: A WordPress plugin to create WPBakery Page Builder blocks.
 * Version: 1.1.1
 * Author: WolfThemes
 * Author URI: http://wolfthemes.com
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: wolf-vc-content-block
 * Domain Path: /languages/
 *
 * @package WolfVcContentBlock
 * @category Core
 * @author WolfThemes
 *
 * Verified customers who have purchased a premium theme at https://wlfthm.es/tf/
 * will have access to support for this plugin in the forums
 * https://wlfthm.es/help/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wolf_Vc_Content_Block' ) ) {
	/**
	 * Main Wolf_Vc_Content_Block Class
	 *
	 * Contains the main functions for Wolf_Vc_Content_Block
	 *
	 * @class Wolf_Vc_Content_Block
	 * @version 1.1.1
	 * @since 1.0.0
	 */
	class Wolf_Vc_Content_Block {

		/**
		 * @var string
		 */
		public $version = '1.1.1';

		/**
		 * @var WPBakery Page Builder Content Blocks The single instance of the class
		 */
		protected static $_instance = null;



		/**
		 * @var the support forum URL
		 */
		private $support_url = 'https://help.wolfthemes.com/';

		/**
		 * @var string
		 */
		public $template_url;

		/**
		 * Main WPBakery Page Builder Content Blocks Instance
		 *
		 * Ensures only one instance of WPBakery Page Builder Content Blocks is loaded or can be loaded.
		 *
		 * @static
		 * @see WVCCB()
		 * @return WPBakery Page Builder Content Blocks - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WPBakery Page Builder Content Blocks Constructor.
		 */
		public function __construct() {

			if ( $this->is_wrong_theme() ) {
				add_action( 'admin_notices', array( $this, 'show_wrong_theme_notice' ) );
				return;
			}

			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				add_action( 'admin_notices', array( $this, 'show_vc_missing_notice' ) );
				return;
			}

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wolf_vc_content_block_loaded' );
		}

		/**
		 * Show notice if your plugin is activated but WPBakery Page Builder is not
		 */
		public function show_vc_missing_notice() {
			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="notice notice-warning">
				<p>' . sprintf(
				wp_kses_post( __( '<strong>%1$s</strong> requires <strong><a href="%2$s" target="_blank">%3$s</a></strong> and <strong><a href="%4$s" target="_blank">%5$s</a></strong> plugins to be installed and activated.', 'wolf-vc-content-block' ) ),
				$plugin_data['Name'],
				'https://wlfthm.es/wpbpb',
				'WPBakery Page Builder',
				'https://wolfthemes.ticksy.com/article/14866',
				'Wolf WPBakery Page Builder Extension'
			) . '</p>
			</div>';
		}

		/**
		 * Show notice if your plugin is activated but WPBakery Page Builder is not
		 */
		public function show_wrong_theme_notice() {
			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="notice notice-warning">
				<p>' . sprintf(
				wp_kses_post( __( 'Sorry, but %1$s only works with compatible <a target="_blank" href="%2$s">%3$s themes</a>.<br><strong>Be sure that you didn\'t change the theme\'s name in the %4$s file or the theme\'s folder name</strong>.<br>If you want to customize the theme\'s name, you can use a <a target="_blank" href="%5$s">child theme</a>.', 'wolf-vc-content-block' ) ),
				$plugin_data['Name'],
				'https://wlfthm.es/tf',
				'WolfThemes',
				'style.css',
				'https://wolfthemes.ticksy.com/article/11659/'
			) . '</p>
			</div>';
		}

		/**
		 * Hook into actions and filters
		 */
		private function init_hooks() {
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
			add_action( 'init', array( $this, 'init' ), 0 );
			add_action( 'wp_head', array( $this, 'add_no_follow_tag' ) );

			// Plugin update notifications
			add_action( 'admin_init', array( $this, 'plugin_update' ) );
		}

		/**
		 * Activation function
		 */
		public function activate() {

			if ( ! get_option( '_wolf_vc_content_block_flush_rewrite_rules_flag' ) ) {
				add_option( '_wolf_vc_content_block_flush_rewrite_rules_flag', true );
			}
		}

		/**
		 * Flush rewrite rules on plugin activation to avoid 404 error on oist type single ^page
		 */
		public function flush_rewrite_rules() {

			if ( get_option( '_wolf_vc_content_block_flush_rewrite_rules_flag' ) ) {
				flush_rewrite_rules();
				delete_option( '_wolf_vc_content_block_flush_rewrite_rules_flag' );
			}
		}

		/**
		 * Define WR Constants
		 */
		private function define_constants() {

			$constants = array(
				'WVCCB_DEV'         => false,
				'WVCCB_DIR'         => $this->plugin_path(),
				'WVCCB_URI'         => $this->plugin_url(),
				'WVCCB_CSS'         => $this->plugin_url() . '/assets/css',
				'WVCCB_JS'          => $this->plugin_url() . '/assets/js',
				'WVCCB_SLUG'        => plugin_basename( dirname( __FILE__ ) ),
				'WVCCB_PATH'        => plugin_basename( __FILE__ ),
				'WVCCB_VERSION'     => $this->version,
				'WVCCB_SUPPORT_URL' => $this->support_url,
			);

			foreach ( $constants as $name => $value ) {
				$this->define( $name, $value );
			}
		}

		/**
		 * Define constant if not already set
		 *
		 * @param  string      $name The constant name.
		 * @param  string|bool $value The constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 *
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			// Functions used in frontend and admin

			if ( $this->is_request( 'admin' ) ) {
				include_once 'inc/admin/class-admin.php';
				include_once 'inc/admin/class-metaboxes.php';
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->frontend_includes();
			}
		}

		/**
		 * Include required frontend files.
		 */
		public function frontend_includes() {
			include_once 'inc/frontend/frontend-functions.php';
			include_once 'inc/frontend/shortcode.php';
		}

		/**
		 * Function used to Init WPBakery Page Builder Content Blocks Template Functions - This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			// include_once( 'inc/frontend/template-functions.php' );
		}

		/**
		 * Init WPBakery Page Builder Content Blocks when WordPress Initialises.
		 */
		public function init() {

			// Set up localisation
			$this->load_plugin_textdomain();

			// Variables
			$this->template_url = apply_filters( 'wolf_vc_content_block_url', 'wolf-vc-block/' );

			$this->register_post_type();
			$this->register_taxonomy();
			$this->flush_rewrite_rules();

			// Init action
			do_action( 'wolf_vc_content_block_init' );
		}

		/**
		 * Register post type
		 */
		public function register_post_type() {
			include_once 'inc/register-post-type.php';
		}

		/**
		 * Register taxonomy
		 */
		public function register_taxonomy() {
			include_once 'inc/register-taxonomy.php';
		}

		/**
		 * Add no follow tag on block single view
		 */
		public function add_no_follow_tag() {
			if ( is_singular( 'wvc_content_block' ) ) {
				echo '<!-- WolfWPBPBContentBlock No Follow -->' . "\n";
				echo '<meta name="robots" content="noindex,follow" />' . "\n";
			}
		}

		/**
		 * Loads the plugin text domain for translation
		 */
		public function load_plugin_textdomain() {

			$domain = 'wolf-vc-content-block';
			$locale = apply_filters( 'wolf-vc-content-block', get_locale(), $domain );
			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Not OK bro
		 *
		 * @return bool
		 */
		private function is_wrong_theme() {
			$ok = array( 'wolf-supertheme', 'wolf-2018', 'protheme', 'iyo', 'loud', 'tune', 'retine', 'racks', 'andre', 'hares', 'glytch', 'superflick', 'phase', 'zample', 'prequelle', 'slikk', 'vonzot', 'deadlift', 'hyperbent', 'kayo', 'reinar', 'snakepit', 'alceste', 'fradence', 'firemaster', 'decibel', 'tattoopress', 'tattoopro', 'milu', 'beatit', 'daeron', 'herion', 'oglin', 'staaw', 'bronze', 'hazal', 'craftz' );

			return ( ! in_array( esc_attr( sanitize_title_with_dashes( get_template() ) ), $ok ) );
		}

		/**
		 * Plugin update
		 */
		public function plugin_update() {

			if ( ! class_exists( 'WP_GitHub_Updater' ) ) {
				include_once 'inc/admin/updater.php';
			}

			$repo = 'wolfthemes/wolf-vc-content-block';

			$config = array(
				'slug'               => plugin_basename( __FILE__ ),
				'proper_folder_name' => 'wolf-vc-content-block',
				'api_url'            => 'https://api.github.com/repos/' . $repo . '',
				'raw_url'            => 'https://raw.github.com/' . $repo . '/master/',
				'github_url'         => 'https://github.com/' . $repo . '',
				'zip_url'            => 'https://github.com/' . $repo . '/archive/master.zip',
				'sslverify'          => true,
				'requires'           => '5.0',
				'tested'             => '5.5',
				'readme'             => 'README.md',
				'access_token'       => '',
			);

			new WP_GitHub_Updater( $config );
		}
	} // end class
} // end class check

/**
 * Returns the main instance of Wolf_Vc_Content_Block to prevent the need to use globals.
 *
 * @return Wolf_Vc_Content_Block
 */
function WVCCB() {
	return Wolf_Vc_Content_Block::instance();
}

WVCCB(); // Go
