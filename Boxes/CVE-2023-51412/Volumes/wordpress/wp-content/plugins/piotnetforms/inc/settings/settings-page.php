<?php
	
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	$activated_license = 1;

	if ( isset($_GET['post']) ) {

		echo '<div class="piotnetforms-builder">';
		echo '<div data-piotnetforms-ajax-url="' . admin_url( 'admin-ajax.php' ) . '"></div>';

		if ( current_user_can( 'edit_others_posts' ) ) {

			$post_id = sanitize_text_field($_GET['post']);
			$piotnetforms_data = get_post_meta( $post_id, '_piotnetforms_data', true );

			$widget_infos = [];

			$editor = new piotnetforms_Editor();
			$widget_object                              = new piotnetforms_Section();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );

			$widget_object                              = new piotnetforms_Column();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();

			$widget_object                              = new piotnetforms_Text();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new Piotnetforms_Field();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );
			
			$widget_object                              = new Piotnetforms_Submit();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new piotnetforms_Button();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new piotnetforms_Image();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new piotnetforms_Icon();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new piotnetforms_Shortcode();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );

			$widget_object                              = new Piotnetforms_Preview_Submissions();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new Piotnetforms_Lost_Password();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new Piotnetforms_Multi_Step_Form();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );

			$widget_object                              = new Piotnetforms_Booking();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			$widget_object                              = new Piotnetforms_Woocommerce_Checkout();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );

			$widget_object                              = new piotnetforms_Icon_List();
			$widget_infos[ $widget_object->get_type() ] = $widget_object->get_info();
			$editor->register_widget( $widget_object );
			echo $editor->register_script( $widget_object );

			echo '<div class="piotnetforms-editor__collapse-button-open" title="Open Editor" data-piotnetforms-editor-collapse-button-open><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-collapse-open.svg"></div>';

			echo '<div class="piotnetforms-editor">';

			echo '<div class="piotnetforms-settings">';
				$editor->editor_panel();
			echo '</div>';

			echo '<div class="piotnetforms-editor__bottom">';
				echo '<div class="piotnetforms-editor__tools">';
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__device-desktop" data-piotnet-control-responsive="desktop"><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-desktop.svg"></div>';
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__device-tablet" data-piotnet-control-responsive="tablet"><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-tablet.svg"></div>';
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__device-mobile" data-piotnet-control-responsive="mobile"><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-mobile.svg"></div>';
					
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__view"><a href="' . get_permalink($post_id) . '" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-view.svg"></a></div>';
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__wp-dashboard"><a href="' . admin_url( 'edit.php?post_type=piotnetforms' ) . '"><i class="fab fa-wordpress-simple"></i></a></div>';
					echo '<div class="piotnetforms-editor__tools-item piotnetforms-editor__go-pro"><a href="https://piotnetforms.com/?wpam_id=1">Go Pro</a></div>';
				echo '</div>';

                echo '<div class="piotnetforms-editor__save" data-piotnetforms-editor-save><i class="far fa-save"></i><i class="icon-spinner-of-dots"></i> Save</div>';
			echo '</div>';

			echo '</div>';

			echo "<div><input type='hidden' name='piotnet-widget-post-id' value='" . esc_attr( $post_id ) . "' data-piotnet-widget-post-id></div>";
			echo "<div><input type='hidden' name='piotnet-widget-breakpoint-tablet' value='1025px' data-piotnet-widget-breakpoint-tablet></div>";
			echo "<div><input type='hidden' name='piotnet-widget-breakpoint-mobile' value='767px' data-piotnet-widget-breakpoint-mobile></div>";

			echo "<div><textarea style='display:none' name='piotnetforms-data' data-piotnetforms-data>{$piotnetforms_data}</textarea></div>";

			echo '<div data-piotnetforms-ajax-url="' . admin_url( 'admin-ajax.php' ) . '"></div>';
			echo '<div data-piotnetforms-stripe-key="' . esc_attr( get_option( 'piotnetforms-stripe-publishable-key' ) ) . '"></div>';

			?>
			<style>
				html.wp-toolbar {
				    padding-top: 0;
				    box-sizing: border-box;
				}

				html, body {
					height: 100%;
					overflow: hidden;
				}

				#wpadminbar {
					display: none;
				}

				#post-body {
					display: flex;
					flex-wrap: wrap;
					margin-right: 0 !important;
				}

				#postbox-container-1 {
					float:right !important;
					margin-right: 0 !important;
					order: 3;
					width: 100% !important;
				}

				#side-sortables {
					width: 100% !important;
				}
			</style>
			<?php
			$controls_manager = new Controls_Manager_Piotnetforms();
			$controls_manager->render();

			echo '<div id="widget_infos" style="display:none">' . json_encode( $widget_infos ) . '</div>';

			$post_url = get_permalink( $post_id );
			$request_parameter = (strpos($post_url, '?') !== false) ? '&' : '?';

				echo '<div class="piotnetforms-preview">';
					echo '<div class="piotnetforms-preview__inner" data-piotnetforms-preview-inner>';
						echo '<iframe class="piotnetforms-preview__iframe" data-piotnetforms-preview-iframe="' . esc_url( $post_url . $request_parameter ) . 'action=piotnetforms"></iframe>';
					echo '</div>';
				echo '</div>';
				echo '<div class="piotnetforms-editor__loading active" data-piotnetforms-editor-loading><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
			echo '</div>';
		}
	} else {
?>
	<div class="wrap">
		<div class="piotnetforms-header">
			<div class="piotnetforms-header__left">
				<div class="piotnetforms-header__logo">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . '../../assets/images/piotnet-logo.png'; ?>" alt="">
				</div>
				<h2 class="piotnetforms-header__headline"><?php esc_html_e( 'Piotnet Forms Settings', 'piotnetforms' ); ?></h2>
			</div>
			<div class="piotnetforms-header__right">
					<a class="piotnetforms-header__button piotnetforms-header__button--gradient" href="https://piotnetforms.com/?wpam_id=1" target="_blank">
					<?php
					if ( $activated_license != 1 ) {
						esc_html_e( 'GO PRO NOW', 'piotnetforms' );
					} else {
						esc_html_e( 'Go to Piotnet Forms', 'piotnetforms' ); }
					?>
					</a>
			</div>
		</div>
		<div class="piotnetforms-wrap">

			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Google Sheets Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
            <div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Google Calendar Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Google Maps Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>
			<br>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Stripe Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php _e('Paypal Integration','piotnetforms'); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'MailChimp Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'MailerLite Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'ActiveCampaign Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>

			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'reCAPTCHA (v3) Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>
			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Twilio Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>
			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php esc_html_e( 'Sendfox Integration', 'piotnetforms' ); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>
			
			<hr>
			<div class="piotnetforms-bottom">
				<div class="piotnetforms-bottom__left">
					<h3><?php _e('Zoho Integration','piotnetforms'); ?></h3>
				</div>
				<div class="piotnetforms-bottom__right">
					<div class="piotnetforms-license">
						<p><a href="https://piotnetforms.com/?wpam_id=1">Please purchase and install Pro version to use this feature, Go Pro Now</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
