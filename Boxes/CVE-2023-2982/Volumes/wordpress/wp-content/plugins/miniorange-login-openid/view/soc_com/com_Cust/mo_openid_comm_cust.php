<?php
function select_comment_customize() {
	?>
	<div class="mo_openid_table_layout">
		<form id="comment_labels" name="comment_labels" method="post" action="" >
			<input type="hidden" name="option" value="mo_openid_comment_labels" />
			<input type="hidden" name="mo_openid_enable_comment_labels_nonce"
				   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-comment-labels-nonce' ) ); ?>"/>
			<br/><table class="mo_openid_display_table" style="width: 70%">
				<tr>
					<td><b><?php echo esc_attr( mo_sl( 'Comment Section Heading' ) ); ?>:</b></td>
					<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 70%" type="text" name="mo_openid_social_comment_heading_label"  value="<?php echo esc_attr( get_option( 'mo_openid_social_comment_heading_label' ) ); ?>" /></td>
				</tr>
				<tr>
					<td><b><?php echo esc_attr( mo_sl( 'Comments - Default Label' ) ); ?>:</b></td>
					<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 70%" type="text" name="mo_openid_social_comment_default_label"  value="<?php echo esc_attr( get_option( 'mo_openid_social_comment_default_label' ) ); ?>" /></td>
				</tr>
				<tr>
					<td><b><?php echo esc_attr( mo_sl( 'Comments - Facebook Label' ) ); ?>:</b></td>
					<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 70%" type="text" name="mo_openid_social_comment_fb_label"  value="<?php echo esc_attr( get_option( 'mo_openid_social_comment_fb_label' ) ); ?>" /></td>
				</tr>
				<tr>
					<td><b><?php echo esc_attr( mo_sl( 'Comments - Disqus Label' ) ); ?>:</b></td>
					<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 70%" type="text" name="mo_openid_social_comment_disqus_label"  value="<?php echo esc_attr( get_option( 'mo_openid_social_comment_disqus_label' ) ); ?>" /></td>
				</tr>
				<tr>
					<td colspan="2"><br /><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:100px;"  class="button button-primary button-large" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script>
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Customize Text For Social Comment Labels' ) ); ?>');
	</script>
	<?php
}
