<?php
/**
 * Content Block Shortcode shortcode
 *
 * @author %AUTHOR%
 * @category Frontend
 * @package %PACKAGENAME%/Frontend
 * @version %VERSION%
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wvb_shortcode' ) ) {
	/**
	 * YouTube shortcode
	 *
	 * @param array $atts
	 * @return string
	 */
	function wvb_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'id' => '0',
		), $atts ) );

		return wccb_block( $id );
	}
	add_shortcode( 'wvc_content_block', 'wvb_shortcode' );
}