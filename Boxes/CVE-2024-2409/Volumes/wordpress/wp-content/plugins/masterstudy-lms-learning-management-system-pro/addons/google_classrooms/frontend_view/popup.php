<?php

$settings = STM_LMS_Google_Classroom::stm_lms_get_settings();

$popup_title  = ( ! empty( $settings['popup_title'] ) ) ? $settings['popup_title'] : '';
$popup_editor = ( ! empty( $settings['popup_editor'] ) ) ? $settings['popup_editor'] : '';
$popup_image  = ( ! empty( $settings['popup_image'] ) ) ? $settings['popup_image'] : '';
$popup_url    = ( ! empty( $settings['popup_link'] ) ) ? $settings['popup_link'] : '';

$bg = wp_get_attachment_image_src( $popup_image, 'full' );
$bg = ( ! empty( $bg ) && ! empty( $bg['0'] ) ) ? $bg['0'] : STM_LMS_PRO_URL . '/addons/google_classrooms/frontend_view/popup.png';

$auditory = STM_LMS_Helpers::get_posts( 'stm-auditory', true );
$auditory = stm_lms_g_c_colors( $auditory, $popup_url );

if ( ! empty( $settings ) && $settings['enable_popup'] && is_front_page() && empty( $_COOKIE['google_classroom_popup'] ) && ( ! empty( $auditory ) || ! empty( $popup_title ) || ! empty( $popup_editor ) ) ) :
	stm_lms_register_style( 'google_classroom/popup' );
	stm_lms_register_style( 'jquery.mCustomScrollbar.min' );

	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
	wp_register_script( 'jquery.mCustomScrollbar.min.js', STM_LMS_URL . 'assets/js/jquery.mCustomScrollbar.min.js', array(), STM_LMS_PRO_VERSION );
	stm_lms_register_script( 'google_classroom_popup', array( 'vue.js', 'jquery.cookie', 'jquery.mCustomScrollbar.min.js' ) );

	$data = array(
		'auditory' => $auditory,
	);

	if ( ! empty( $bg ) ) {
		$data['bg'] = $bg;
	}

	wp_localize_script( 'stm-lms-google_classroom_popup', 'google_classroom_popup', $data );

	?>

	<div id="google_classroom_popup" :class="'show_popup_' + show_popup">

		<div class="google_classroom_popup">

			<div class="google_classroom_popup__close" @click="show_popup = false">
				<i class="lnricons-cross"></i>
			</div>

			<div class="google_classroom_popup__inner">

				<div class="google_classroom_popup__bg" :style="{'background-image' : 'url(' + bg + ')'}"></div>

				<div class="google_classroom_popup__data">

					<?php if ( ! empty( $popup_title ) ) : ?>
						<h3 class="google_classroom_popup__title"><?php echo esc_html( $popup_title ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $popup_editor ) ) : ?>
						<div class="google_classroom_popup__content">
							<?php echo wp_kses_post( $popup_editor ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $auditory ) ) : ?>
						<div class="form-group google_classroom_popup__search">
							<input type="text"
									v-model="search"
									class="form-control"
									placeholder="<?php esc_attr_e( 'Type your classname', 'masterstudy-lms-learning-management-system-pro' ); ?>"/>
							<i class="fa fa-search"></i>
						</div>
					<?php endif; ?>


				</div>


				<?php if ( ! empty( $auditory ) ) : ?>

					<div class="google_classroom_popup__auditories_wrapper">
						<div class="google_classroom_popup__auditories">
							<a class="google_classroom_popup__auditory heading_font"
								:href="auditory.url"
								v-for="auditory in auditoriesList"
								:style="{'background-color' : auditory.color}">
								<span v-html="auditory.name"></span>
							</a>
						</div>
					</div>

				<?php endif; ?>

			</div>

		</div>

		<div class="google_classroom_popup__overlay" @click="show_popup = false"></div>

	</div>


	<?php
endif;
