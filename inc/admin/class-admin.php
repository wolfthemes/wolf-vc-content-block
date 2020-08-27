<?php
/**
 * WPBakery Page Builder Content Blocks Admin.
 *
 * @class Wolf_Vc_Content_Block_Admin
 * @author WolfThemes
 * @category Admin
 * @package WolfVcContentBlock/Admin
 * @version 1.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Wolf_Vc_Content_Block_Admin class.
 */
class Wolf_Vc_Content_Block_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {

		// Update
		add_action( 'admin_init', array( $this, 'update' ), 0 );
	}

	/**
	 * Perform actions on updating the theme id needed
	 */
	public function update() {

		if ( ! defined( 'IFRAME_REQUEST' ) && ! defined( 'DOING_AJAX' ) && ( get_option( 'wolf_vc_content_block_version' ) != WVCCB_VERSION ) ) {

			// Update hook
			do_action( 'wolf_vc_content_block_do_update' );

			// Update version
			delete_option( 'wolf_vc_content_block_version' );
			add_option( 'wolf_vc_content_block_version', WVCCB_VERSION );

			// After update hook
			do_action( 'wolf_vc_content_block_updated' );
		}
	}
}

return new Wolf_Vc_Content_Block_Admin();
