<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wolf_Vc_Content_Block_Metaboxes' ) ) {
	/**
	 * Main Wolf_Vc_Content_Block_Metaboxes Class
	 *
	 * Contains the main functions for Wolf_Vc_Content_Block_Metaboxes
	 *
	 * @class Wolf_Vc_Content_Block_Metaboxes
	 * @version 1.0.9
	 * @since 1.0.0
	 */
	class Wolf_Vc_Content_Block_Metaboxes {

		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct() {

			if ( ! defined( 'WOLF_SUPER_USER' ) ) {
				return;
			}

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}

		/**
		 * Adds the meta box container
		 *
		 * @param string $post_type
		 */
		public function add_meta_box( $post_type ) {
			$post_types = array( 'wvc_content_block' ); // limit meta box to certain post types
			if ( in_array( $post_type, $post_types ) ) {
				add_meta_box(
					'wolf_vc_content_block_split'
					,esc_html__( 'A/B testing', 'wolf' )
					,array( $this, 'render_meta_box_content' )
					,$post_type
					,'side'
					,'high'
				);
			}
		}

		/**
		 * Save the meta when the post is saved.
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		public function save( $post_id ) {

			

			// Check if our nonce is set.
			if ( ! isset( $_POST['wolf_vc_content_block_split_inner_custom_box_nonce'] ) )
				return $post_id;

			$nonce = $_POST['wolf_vc_content_block_split_inner_custom_box_nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'wolf_vc_content_block_split_inner_custom_box' ) )
				return $post_id;

			// If this is an autosave, our form has not been submitted,
			// so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			// Check the user's permissions.
			if ( 'page' == $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) )
					return $post_id;

			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}

			/* OK, its safe for us to save the data now. */

			// Sanitize the user input.
			$experiment_id = esc_attr( $_POST['wvc_content_block_go_experiment_id'] );
			$clone_id = absint( $_POST['wvc_content_block_split_clone'] );

			// Update the meta field.
			update_post_meta( $post_id, '_wvc_content_block_go_experiment_id', $experiment_id );
			update_post_meta( $post_id, '_wvc_content_block_split_clone_id', $clone_id );
		}


		/**
		 * Render Meta Box content.
		 *
		 * @param int $post The post object.
		 */
		public function render_meta_box_content( $post ) {

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'wolf_vc_content_block_split_inner_custom_box', 'wolf_vc_content_block_split_inner_custom_box_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$clone_id = get_post_meta( $post->ID, '_wvc_content_block_split_clone_id', true );
			$experiment_id = get_post_meta( $post->ID, '_wvc_content_block_go_experiment_id', true );
			$is_clone = false;
			$clone_title = null;
			
			// list content block
			$content_block_posts = get_posts( 'post_type="wvc_content_block"&numberposts=-1' );
			$content_blocks = array(
				'' => '&mdash; ' . esc_html__( 'None', 'wolf-vc-content-block' ) . ' &mdash;',
			);

			if ( $content_block_posts ) {

				foreach ( $content_block_posts as $content_block_post ) {

					if ( $post->ID === get_post_meta( $content_block_post->ID, '_wvc_content_block_split_clone_id', true ) ) {
						$is_clone = true;
						$clone_title = $content_block_post->post_title;
						break;
					}
					
					if ( $content_block_post->ID != $post->ID ) {
						$content_blocks[ $content_block_post->ID ] = $content_block_post->post_title;
					}
				}
			
			} else {
				$content_blocks[0] = esc_html__( 'No Other Content Block Yet', 'wolf-vc-content-block' );
			}

			if ( $is_clone ) {

				esc_html_e( 'Is clone of:', '%TEXDOMAIN%' );
				echo '<br><strong>';
				echo $clone_title;
				echo '</strong>';

			} else {

				echo '<p><label for="wvc_content_block_go_experiment_id">';
				esc_html_e( 'Google Optimize Experiment ID:', '%TEXDOMAIN%' );
				echo '</label></p>';
				echo '<p><input placeholder="16iQisXuS1qwXDixwB-EWgQ" type="text" id="wvc_content_block_go_experiment_id" name="wvc_content_block_go_experiment_id"';
				echo ' value="' . esc_attr( $experiment_id ) . '" size="25" /></p>';
				

				echo '<p><select name="wvc_content_block_split_clone">';
				foreach ( $content_blocks as $id => $title ) {
					echo '<option ' . selected( absint( $id ) , absint( $clone_id ) ) . ' value="' . absint( $id ) . '">' . esc_attr( $title ) . '</option>';
				}
				echo '</select></p>';
			}
		}

	} // end class

	$wolf_vc_content_block_split = new Wolf_Vc_Content_Block_Metaboxes();

} // class_exists check
