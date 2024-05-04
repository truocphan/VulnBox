<?php
	function piotnetforms_shortcode_in_post_meta_box() {
		add_meta_box( 'piotnetforms-shortcode-in-post-meta-box', 'Piotnetforms Shortcode in this Post/Page', 'piotnetforms_shortcode_in_post_meta_box_output', ['post', 'page'] );
	}
	add_action( 'add_meta_boxes', 'piotnetforms_shortcode_in_post_meta_box' );

	function piotnetforms_shortcode_in_post_meta_box_output( $post ) {
		$piotnetforms_shortcode_in_post = get_post_meta( $post->ID, '_piotnetforms_shortcode_in_post', true );
		?>
			<table class="form-table">
		        <tr valign="top">
			        <td><?php _e('Enter each of Piotnetforms Shortcode in this Post/Page, separated by pipe char ("|").','piotnetforms'); ?></td>
			    </tr>
			    <tr valign="top">
		        	<td><input type="text" class="regular-text" id="piotnetforms_shortcode_in_post" name="piotnetforms_shortcode_in_post" value="<?php echo esc_attr( $piotnetforms_shortcode_in_post ); ?>" /></td>
		        </tr>
		    </table>
		<?php
	}

	function piotnetforms_shortcode_in_post_meta_box_save( $post_id ) {
		if (isset($_POST['piotnetforms_shortcode_in_post'])) {
			$piotnetforms_shortcode_in_post = sanitize_text_field( $_POST['piotnetforms_shortcode_in_post'] );
			update_post_meta( $post_id, '_piotnetforms_shortcode_in_post', $piotnetforms_shortcode_in_post );
		} else {
		    delete_post_meta( $post_id, '_piotnetforms_shortcode_in_post' );
		}
	}
	add_action( 'save_post', 'piotnetforms_shortcode_in_post_meta_box_save' );