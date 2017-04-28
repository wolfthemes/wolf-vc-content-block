<?php
/**
 * %NAME% register post type
 *
 * @author %AUTHOR%
 * @category Core
 * @package %PACKAGENAME%/Admin
 * @version %VERSION%
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Register Content Block post type */
$labels = array(
	'name' => esc_html__( 'Content Blocks', '%TEXTDOMAIN%' ),
	'singular_name' => esc_html__( 'Content Block', '%TEXTDOMAIN%' ),
	'add_new' => esc_html__( 'Add New', '%TEXTDOMAIN%' ),
	'add_new_item' => esc_html__( 'Add New Content Block', '%TEXTDOMAIN%' ),
	'all_items'  => esc_html__( 'All Content Blocks', '%TEXTDOMAIN%' ),
	'edit_item' => esc_html__( 'Edit Content Block', '%TEXTDOMAIN%' ),
	'new_item' => esc_html__( 'New Content Block', '%TEXTDOMAIN%' ),
	'view_item' => esc_html__( 'View Content Block', '%TEXTDOMAIN%' ),
	'search_items' => esc_html__( 'Search Content Blocks', '%TEXTDOMAIN%' ),
	'not_found' => esc_html__( 'No Content Blocks found', '%TEXTDOMAIN%' ),
	'not_found_in_trash' => esc_html__( 'No Content Blocks found in Trash', '%TEXTDOMAIN%' ),
	'parent_item_colon' => '',
	'menu_name' => esc_html__( 'Content Block', '%TEXTDOMAIN%' ),
);

$args = array(
	'labels' => $labels,
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'query_var' => false,
	'rewrite' => array( 'slug' => 'content-block' ),
	'capability_type' => 'post',
	'has_archive' => false,
	'hierarchical' => false,
	'menu_position' => 5,
	'taxonomies' => array(),
	'supports' => array( 'title', 'editor' ),
	'exclude_from_search' => false,
	'description' => esc_html__( 'Present your work', '%TEXTDOMAIN%' ),
	'menu_icon' => 'dashicons-editor-table',
);

register_post_type( 'wvc_content_block', $args );