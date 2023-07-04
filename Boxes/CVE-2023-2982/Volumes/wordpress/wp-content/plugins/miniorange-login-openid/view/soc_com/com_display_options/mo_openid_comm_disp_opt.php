<?php
function select_comment_disp() {
	?>

	<div class="mo_openid_table_layout">
		<form id="comment_display" name="comment_display" method="post" action="" >
			<input type="hidden" name="option" value="mo_openid_comment_display" />
			<input type="hidden" name="mo_openid_enable_comment_display_nonce"
				   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-comment-display-nonce' ) ); ?>"/>
			<br/>
			<table id="disp_opt" class="mo_openid_display_table">
				<tr>
					<td>
						<?php echo esc_attr( mo_sl( 'Select the options where you want to add social comments' ) ); ?>.
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<label class="mo_openid_checkbox_container"> <b><?php echo esc_attr( mo_sl( 'Blog Post' ) ); ?></b>
							<input type="checkbox" id="mo_openid_social_comment_blogpost" name="mo_openid_social_comment_blogpost" value="1"  <?php checked( get_option( 'mo_openid_social_comment_blogpost' ) == 1 ); ?> />
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label class="mo_openid_checkbox_container"> <b><?php echo esc_attr( mo_sl( 'Static Pages' ) ); ?></b>
							<input type="checkbox" id="mo_openid_social_comment_static" name="mo_openid_social_comment_static" value="1"  <?php checked( get_option( 'mo_openid_social_comment_static' ) == 1 ); ?> />
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
					</td>
				</tr>
			</table>
			<div><br/>
				<input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;text-shadow: none;background-color:#0867b2;color:white;box-shadow:none;"  class="button button-primary button-large" />
			</div>
		</form>
	</div>
	<script>
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Display Options' ) ); ?>');
	</script>
<?php }
?>
