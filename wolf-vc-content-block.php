<?php
/**
 * Plugin Name: Wolf VC Content Block
 * Plugin URI: %LINK%
 * Description: %DESCRIPTION%
 * Version: %VERSION%
 * Author: %AUTHOR%
 * Author URI: %AUTHORURI%
 * Requires at least: %REQUIRES%
 * Tested up to: %TESTED%
 *
 * Text Domain: %TEXTDOMAIN%
 * Domain Path: /languages/
 *
 * @package %PACKAGENAME%
 * @category Core
 * @author %AUTHOR%
 *
 * Being a free product, this plugin is distributed as-is without official support.
 * Verified customers however, who have purchased a premium theme
 * at https://themeforest.net/user/Wolf-Themes/portfolio?ref=Wolf-Themes
 * will have access to support for this plugin in the forums
 * https://help.wolfthemes.com/
 *
 * Copyright (C) 2017 Constantin Saguin
 * This WordPress Plugin is a free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * It is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * See https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wolf_Vc_Content_Block' ) ) {
	/**
	 * Main Wolf_Vc_Content_Block Class
	 *
	 * Contains the main functions for Wolf_Vc_Content_Block
	 *
	 * @class Wolf_Vc_Content_Block
	 * @version %VERSION%
	 * @since 1.0.0
	 */
	class Wolf_Vc_Content_Block {

		/**
		 * @var string
		 */
		public $version = '%VERSION%';

		/**
		 * @var %NAME% The single instance of the class
		 */
		protected static $_instance = null;

		/**
		 * @var string
		 */
		private $update_url = 'https://plugins.wolfthemes.com/update';

		/**
		 * @var the support forum URL
		 */
		private $support_url = 'https://help.wolfthemes.com/';

		/**
		 * @var string
		 */
		public $template_url;

		/**
		 * Main %NAME% Instance
		 *
		 * Ensures only one instance of %NAME% is loaded or can be loaded.
		 *
		 * @static
		 * @see WVC()
		 * @return %NAME% - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '%TEXTDOMAIN%' ), '1.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '%TEXTDOMAIN%' ), '1.0' );
		}

		/**
		 * %NAME% Constructor.
		 */
		public function __construct() {

			if ( $this->not_ok_bro() ) {

				$log = esc_html__( 'Sorry, but you can not use Wolf VC plugin with this theme.', '%TEXTDOMAIN%' );

				trigger_error( $log );

				return false;
			}

			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				// Display notice that Visual Composer is required
				add_action( 'admin_notices', array( $this, 'show_vc_missing_notice' ) );
				return;
			}

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wolf_vc_content_block_loaded' );
		}

		/**
		 * Show notice if your plugin is activated but Visual Composer is not
		 */
		public function show_vc_missing_notice() {
			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="updated">
				<p>' . sprintf(
					__('<strong>%s</strong> requires <strong><a href="%s" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', '%TEXTDOMAIN%' ),
						$plugin_data['Name'],
						'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=wolf-themes'
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
				'WVCCB_DEV' => false,
				'WVCCB_DIR' => $this->plugin_path(),
				'WVCCB_URI' => $this->plugin_url(),
				'WVCCB_CSS' => $this->plugin_url() . '/assets/css',
				'WVCCB_JS' => $this->plugin_url() . '/assets/js',
				'WVCCB_SLUG' => plugin_basename( dirname( __FILE__ ) ),
				'WVCCB_PATH' => plugin_basename( __FILE__ ),
				'WVCCB_VERSION' => $this->version,
				'WVCCB_UPDATE_URL' => $this->update_url,
				'WVCCB_SUPPORT_URL' => $this->support_url,
			);

			foreach ( $constants as $name => $value ) {
				$this->define( $name, $value );
			}
		}

		/**
		 * Define constant if not already set
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			// Functions used in frontend and admin

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'inc/admin/class-admin.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->frontend_includes();
			}
		}

		/**
		 * Include required frontend files.
		 */
		public function frontend_includes() {
			include_once( 'inc/frontend/frontend-functions.php' );
			include_once( 'inc/frontend/shortcode.php' );
		}

		/**
		 * Function used to Init %NAME% Template Functions - This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			//include_once( 'inc/frontend/template-functions.php' );
		}

		/**
		 * Init %NAME% when WordPress Initialises.
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
			include_once( 'inc/register-post-type.php' );
		}

		/**
		 * Register taxonomy
		 */
		public function register_taxonomy() {
			include_once( 'inc/register-taxonomy.php' );
		}

		/**
		 * Add no follow tag on block single view
		 */
		public function add_no_follow_tag() {
			if ( is_singular( 'wvc_content_block' ) ) {
				echo "<!-- WolfVCContentBlock No Follow -->" . "\n";
				echo '<meta name="robots" content="noindex,follow" />' . "\n";
			}
		}

		/**
		 * Loads the plugin text domain for translation
		 */
		public function load_plugin_textdomain() {

			$domain = '%TEXTDOMAIN%';
			$locale = apply_filters( '%TEXTDOMAIN%', get_locale(), $domain );
			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Not OK bro
		 * @return bool
		 */
		private function not_ok_bro() {
			$ok = array( 'wolf-lite', 'protheme', 'loud' );

			return ( ! in_array( esc_attr( sanitize_title_with_dashes( get_template() ) ), $ok ) );
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