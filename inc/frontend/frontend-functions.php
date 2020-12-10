<?php
/**
 * Frontend functions
 *
 * @author WolfThemes
 * @category Frontend
 * @package WolfVcContentBlock/Frontend
 * @version 1.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gets the ID of the post, even if it's not inside the loop.
 *
 * @uses WP_Query
 * @uses get_queried_object()
 * @extends get_the_ID()
 * @see get_the_ID()
 *
 * @return int
 */
function wccb_get_the_ID() {
	global $wp_query;

	$post_id = null;

	// Get post ID outside the loop
	if ( is_object( $wp_query ) && isset( $wp_query->queried_object ) && isset( $wp_query->queried_object->ID ) ) {
		$post_id = $wp_query->queried_object->ID;
	} else {
		$post_id = get_the_ID();
	}

	return $post_id;
}

/**
 * Return block content
 */
function wccb_block( $post_id ) {

	$post_id = absint( apply_filters( 'wpml_object_id', $post_id, 'post' ) ); // WPML compatibility

	// Be sure that the post exists and that it is not the current post
	if ( get_post_status( $post_id ) && $post_id !== get_the_ID() && 'wvc_content_block' === get_post_type( $post_id ) ) {

		$output = '';

		$clone_id = get_post_meta( $post_id, '_wvc_content_block_split_clone_id', true );
		$output_id = $post_id;

		/* A/B test */
		if ( isset( $_COOKIE['wvc_content_block_split_clone_' . $post_id] ) ) {

			$output_id = absint( $_COOKIE['wvc_content_block_split_clone_' . $post_id] );

		} elseif ( $clone_id ) {

			$output_id = rand( 0, 1 ) ? $post_id : $clone_id;
		}

		if ( $clone_id ) {

			$exp_id = get_post_meta( $post_id, '_wvc_content_block_go_experiment_id', true );

			$exp = $exp_id . '.' . $output_id;

			$output .= '<script>';

			// Set cookie if needed
			if ( ! isset( $_COOKIE['wvc_content_block_split_clone_' . $post_id] ) ) {

				$output .= "jQuery( function( $ ) {
						if ( 'undefined' !== typeof( Cookies ) ) {
							Cookies.set( 'wvc_content_block_split_clone_$post_id', '$output_id', { expires: 30, path: '/' } );
						}
					} );";
			}

			// track GA exp
			$output .= "jQuery(function($) { if ( 'undefined' !== typeof( ga ) ) { ga( 'set', 'exp', '$exp' ); } } );";

			$output .= '</script>';

		}

		$output .= wvccb_js_remove_wpautop( get_post_field( 'post_content', $output_id ) );

		return $output;
	}
}

/**
 * Straight from VC
 *
 * @param $content
 * @param bool $autop
 * @return string
 */
function wvccb_js_remove_wpautop( $content, $autop = false ) {

	if ( $autop ) { // Possible to use !preg_match('('.WPBMap::getTagsRegexp().')', $content)
		$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
	}

	return do_shortcode( shortcode_unautop( $content ) );
}

/**
 * Get all visible block IDs in a post
 *
 * @param int $post_id
 *
 * Not used
 */
function  wccp_get_block_ids_in_page( $post_id = null ) {

	$post_id = ( isset( $post_id ) ) ? $post_id : wccb_get_the_ID();

	$post = get_post( $post_id );

	$post_content =  $post->post_content;
	$pattern = get_shortcode_regex();
	$pattern = '[wvc_content_block id="[0-9]+"]';
	$block_ids = array();

	if (   preg_match_all( '/'.$pattern.'/s', $post_content, $matches ) ) {

		if ( isset( $matches[0] ) ) {

			foreach ( $matches[0] as $match ) {
				$block_id = preg_replace("/[^0-9]/", '', $match );
				$block_ids[] = $block_id;
			}
		}
	}

	return $block_ids;
}

/**
 * Set WooCommerce analitycs experient tracker
 *
 * No used
 */
function wccb_set_block_experiment_trackers( $ga_snippet_require ) {

	if ( ! defined( 'WOLF_SUPER_USER' ) ) {
		return;
	}

	if ( ! is_page() ) {
		//return;
	}

	$post_id = wccb_get_the_ID();

	if ( array() !== wccp_get_block_ids_in_page( $post_id ) ) {

		$block_ids = wccp_get_block_ids_in_page( $post_id );

		foreach ( $block_ids as $post_id ) {

			$clone_id = get_post_meta( $post_id, '_wvc_content_block_split_clone_id', true );
			$exp_id = get_post_meta( $post_id, '_wvc_content_block_go_experiment_id', true );
			$cookie_id = 'wvc_content_block_split_clone_' . $post_id;
			$cookie = isset( $_COOKIE[ $cookie_id ] ) ? $_COOKIE[ $cookie_id ] : null;
			$issetcookie = isset( $_COOKIE[ $cookie_id ] );

			if ( $cookie ) {
				$post_id = $cookie;
			}

			if ( $clone_id ) {

				$exp = $exp_id . '.' . $post_id . ' : ' . $issetcookie;

				$ga_snippet_require .= "" . WC_Google_Analytics_JS::tracker_var() . "( 'set', 'exp', '$exp' );";
			}
		}
	}

	return $ga_snippet_require;
}
//add_filter( 'woocommerce_ga_snippet_require', 'wccb_set_block_experiment_trackers', 999 );

/**
 * Set block experiment cookie
 *
 * not used
 */
function wccb_set_block_experiment_cookies( $post_id ) {

	if ( ! defined( 'WOLF_SUPER_USER' ) ) {
		return;
	}

	$post_id = wccb_get_the_ID();

	if ( array() !== wccp_get_block_ids_in_page( $post_id ) ) {

		$block_ids = wccp_get_block_ids_in_page( $post_id );

		foreach ( $block_ids as $post_id ) {

			$clone_id = get_post_meta( $post_id, '_wvc_content_block_split_clone_id', true );
			$cookie_id = 'wvc_content_block_split_clone_' . $post_id;
			$cookie_duration = 7 * DAY_IN_SECONDS;
			$cookie = isset( $_COOKIE[ $cookie_id ] ) ? $_COOKIE[ $cookie_id ] : null;

			if ( ! $cookie && $clone_id ) {
				$random_id = rand( 0, 1 ) ? $post_id : $clone_id;
				setcookie( 'wvc_content_block_split_clone_' . $post_id, $random_id, time() + $cookie_duration );
			}
		}
	}
}
//add_action( 'wp', 'wccb_set_block_experiment_cookies' );
