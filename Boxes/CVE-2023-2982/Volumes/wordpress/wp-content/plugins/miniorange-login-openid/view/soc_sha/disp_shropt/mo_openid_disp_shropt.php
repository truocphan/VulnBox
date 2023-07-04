<?php
function mo_openid_display_share_opt() {
	?>

	<br>
	<form id="share_display" name="share_display" method="post" action="">
	<input type="hidden" name="option" value="mo_openid_enable_share_display" />
	<input type="hidden" name="mo_openid_enable_share_display_nonce"
		   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-share-display-nonce' ) ); ?>"/>

			<div class="mo_openid_table_layout" >
				<table style="height: 200%">
					<style>
						#upleft {
							width:45%;
							height:330px;
							background:white;
							float:left;
							border: 1px transparent;
						}
					 #upright {
							width:45%;
							height:380px;
							background:white;
							float:right;
							border: 1px transparent;
						}
						#below {
							height:available;
							display: inline;
							width:100%;
							background:white;
							float:right;
							border: 1px transparent;
							padding-bottom: 10px;
						}
						#above{
							height:50px;
							width:100%;
							background:white;
							float:right;
							border: 1px transparent;
						}
					</style>
					<div id="above">
		   <strong style="font-size: 14px"><?php echo esc_attr( mo_sl( 'Select the options where you want to display social share icons' ) ); ?></strong>
					</div>
					<div id="upleft" style="font-size: 14px;">


						<label class="mo_openid_checkbox_container"> <?php echo esc_attr( mo_sl( 'Home Page' ) ); ?>
							<input type="checkbox" id="mo_apps_home_page"  name="mo_share_options_home_page"  value="1"  <?php checked( get_option( 'mo_share_options_enable_home_page' ) == 1 ); ?>>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<div style="margin-left: 8%">

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="before"  <?php checked( get_option( 'mo_share_options_home_page_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="after"  <?php checked( get_option( 'mo_share_options_home_page_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="both"  <?php checked( get_option( 'mo_share_options_home_page_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

					</div>

							   <br>


						<label class="mo_openid_checkbox_container"> <?php echo esc_attr( mo_sl( 'Blog Post' ) ); ?>
							<input type="checkbox" id="mo_apps_posts"  name="mo_share_options_post" value="1"  <?php checked( get_option( 'mo_share_options_enable_post' ) == '1' ); ?>>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<div style="margin-left: 8%">


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="before"  <?php checked( get_option( 'mo_share_options_enable_post_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="after"  <?php checked( get_option( 'mo_share_options_enable_post_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>



						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="both"  <?php checked( get_option( 'mo_share_options_enable_post_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>
					</div>

						<br>

						<label class="mo_openid_checkbox_container"> <?php echo esc_attr( mo_sl( 'Static Pages' ) ); ?>
							<input type="checkbox" id="mo_apps_static_page"  name="mo_share_options_static_pages"  value="1"  <?php checked( get_option( 'mo_share_options_enable_static_pages' ) == 1 ); ?>>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<div style="margin-left: 8%">


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="before"  <?php checked( get_option( 'mo_share_options_static_pages_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="after"  <?php checked( get_option( 'mo_share_options_static_pages_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="both"  <?php checked( get_option( 'mo_share_options_static_pages_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>
					</div>
						<br>

						<label class="mo_openid_checkbox_container_disable "> <?php echo esc_attr( mo_sl( ' WooCommerce Individual Product Page(Top)' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
							<input type="checkbox" id="mo_apps_wc_sp_page_top"  name="mo_share_options_wc_sp_summary_top"  value="1"  >
							<span class="mo_openid_checkbox_checkmark "></span>
						</label>
						<label class="mo_openid_checkbox_container_disable "> <?php echo esc_attr( mo_sl( 'Below WooCommerce Product' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
							<input type="checkbox" id="mo_apps_wc_product"  name="mo_share_options_wc_product"  value="1" >
						<span class="mo_openid_checkbox_checkmark "></span>
						</label>

					</div>
					<div id="upright" style="font-size: 14px;">
						<br>

						<label class="mo_openid_checkbox_container_disable"> <?php echo esc_attr( mo_sl( 'WooCommerce Individual Product Page' ) ); ?> <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
							<input type="checkbox" id="mo_apps_wc_sp_page"  name="mo_share_options_wc_sp_summary"  value="1">
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>

						<label class="mo_openid_checkbox_container" ">  <?php echo esc_attr( mo_sl( 'BBPress Forums Page' ) ); ?>
						<input type="checkbox" id="mo_apps_bb_forum"  name="mo_share_options_bb_forum"  value="1"  <?php checked( get_option( 'mo_share_options_bb_forum' ) == 1 ); ?>>
						<span class="mo_openid_checkbox_checkmark "></span>
						</label>
						<div style="margin-left: 8%">
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="before"  <?php checked( get_option( 'mo_share_options_bb_forum_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="after" <?php checked( get_option( 'mo_share_options_bb_forum_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="both"  <?php checked( get_option( 'mo_share_options_bb_forum_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>
					</div>
						<br>

						<label class="mo_openid_checkbox_container"> <?php echo esc_attr( mo_sl( 'BBPress Topic Page' ) ); ?>
							<input type="checkbox" id="mo_apps_bb_topic"  name="mo_share_options_bb_topic"  value="1"  <?php checked( get_option( 'mo_share_options_bb_topic' ) == 1 ); ?>>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
						<div style="margin-left: 8%">
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="before"  <?php checked( get_option( 'mo_share_options_bb_topic_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="after"  <?php checked( get_option( 'mo_share_options_bb_topic_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="both"  <?php checked( get_option( 'mo_share_options_bb_topic_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						</div>

						<br>


						<label class="mo_openid_checkbox_container"> <?php echo esc_attr( mo_sl( 'BBPress Reply Page' ) ); ?>
							<input type="checkbox" id="mo_apps_bb_reply"  name="mo_share_options_bb_reply"  value="1"  <?php checked( get_option( 'mo_share_options_bb_reply' ) == 1 ); ?>>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>

<div style="margin-left: 8%">

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Before content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="before"  <?php checked( get_option( 'mo_share_options_bb_reply_position' ) == 'before' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'After content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="after"  <?php checked( get_option( 'mo_share_options_bb_reply_position' ) == 'after' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>


						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Both before and after content' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="both"  <?php checked( get_option( 'mo_share_options_bb_reply_position' ) == 'both' ); ?>>
							<span class="mo-openid-radio-checkmark"></span></label>

</div>
					</div>
					<div id="below" style="margin: 14px">
						<p class="mo_openid_note_style" ><strong><?php echo esc_attr( mo_sl( 'NOTE' ) ); ?>:</strong> <?php echo esc_attr( mo_sl( 'The icons in above pages will be placed horizontally. For vertical icons, add ' ) ); ?><b><?php echo esc_attr( mo_sl( 'miniOrange Sharing - Vertical' ) ); ?></b> <?php echo esc_attr( mo_sl( 'widget from Appearance->Widgets.' ) ); ?></p>

					</div>
					<tr>
						<td><br /><b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" /></b>
						</td>
					</tr>

				</table>
	</div>
	</form>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Display Options' ) ); ?>');
	</script>

	<?php
}
?>
