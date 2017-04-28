<?php
/**
 * %NAME% register taxonomy
 *
 * @author %AUTHOR%
 * @category Core
 * @package %PACKAGENAME%/Admin
 * @version %VERSION%
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Content Block Taxonomy */
$labels = array(
	'name' => esc_html__( 'Content Block Categories', '%TEXTDOMAIN%' ),
	'singular_name' => esc_html__( 'Content Block Category', '%TEXTDOMAIN%' ),
	'search_items' => esc_html__( 'Search Content Block Categories', '%TEXTDOMAIN%' ),
	'popular_items' => esc_html__( 'Popular Content Block Categories', '%TEXTDOMAIN%' ),
	'all_items' => esc_html__( 'All Content Block Categories', '%TEXTDOMAIN%' ),
	'parent_item' => esc_html__( 'Parent Content Block Category', '%TEXTDOMAIN%' ),
	'parent_item_colon' => esc_html__( 'Parent Content Block Category:', '%TEXTDOMAIN%' ),
	'edit_item' => esc_html__( 'Edit Content Block Category', '%TEXTDOMAIN%' ),
	'update_item' => esc_html__( 'Update Content Block Category', '%TEXTDOMAIN%' ),
	'add_new_item' => esc_html__( 'Add New Content Block Category', '%TEXTDOMAIN%' ),
	'new_item_name' => esc_html__( 'New Content Block Category', '%TEXTDOMAIN%' ),
	'separate_items_with_commas' => esc_html__( 'Separate content block categories with commas', '%TEXTDOMAIN%' ),
	'add_or_remove_items' => esc_html__( 'Add or remove content block categories', '%TEXTDOMAIN%' ),
	'choose_from_most_used' => esc_html__( 'Choose from the most used content block categories', '%TEXTDOMAIN%' ),
	'menu_name' => esc_html__( 'Categories', '%TEXTDOMAIN%' ),
);

$args = array(
	'labels' => $labels,
	'hierarchical' => true,
	'public' => true,
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => array( 'slug' => 'content-block-category', 'with_front' => false ),
);

register_taxonomy( 'wvc_content_block_category', array( 'wvc_content_block' ), $args );