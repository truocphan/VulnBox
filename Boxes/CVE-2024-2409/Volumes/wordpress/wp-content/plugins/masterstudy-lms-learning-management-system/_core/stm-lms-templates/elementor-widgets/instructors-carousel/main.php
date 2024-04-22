<div class="ms_lms_instructors_carousel <?php echo ( ! empty( $rtl ) ) ? 'rtl' : ''; ?>">
	<div class="ms_lms_instructors_carousel_wrapper">
		<div dir="<?php echo ( ! empty( $rtl ) ) ? 'rtl' : 'ltr'; ?>" class="ms_lms_instructors_carousel__header <?php echo ( ! empty( $widget_header_presets ) ) ? esc_attr( $widget_header_presets ) : 'style_1'; ?> <?php echo ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'side' === $navigation_arrows_position ) ? 'side_navigation' : ''; ?>">
			<?php
			if ( ! empty( $widget_title ) ) {
				?>
				<h2 class="ms_lms_instructors_carousel__header_title">
					<?php echo esc_html( $widget_title ); ?>
				</h2>
				<?php
			}
			if ( ! empty( $widget_description ) ) {
				?>
			<p class="ms_lms_instructors_carousel__header_description">
				<?php echo esc_html( $widget_description ); ?>
			</p>
				<?php
			}
			if ( ! empty( $show_navigation ) && 'view_all' === $navigation_type ) {
				?>
				<a dir="ltr" class="ms_lms_instructors_carousel__header_view_all" href="<?php echo esc_url( ( ! empty( $view_all_button_link['url'] ) ) ? $view_all_button_link['url'] : STM_LMS_Instructor::get_instructors_url() ); ?>">
					<?php esc_html_e( 'View all', 'masterstudy-lms-learning-management-system' ); ?>
					<i class="lnr lnr-arrow-right"></i>
				</a>
				<?php
			}
			if ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'top' === $navigation_arrows_position ) {
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/instructors-carousel/navigation/top',
					array(
						'show_navigation'            => $show_navigation,
						'navigation_type'            => $navigation_type,
						'navigation_arrows_position' => $navigation_arrows_position,
						'navigation_arrows_presets'  => $navigation_arrows_presets,
					)
				);
			}
			?>
		</div>
		<?php if ( ! empty( $instructors ) ) { ?>
			<div dir="<?php echo ( ! empty( $rtl ) ) ? 'rtl' : 'ltr'; ?>" class="ms_lms_instructors_carousel__content_wrapper <?php echo ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'side' === $navigation_arrows_position ) ? 'row' : ''; ?>">
				<?php
				if ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'bottom' === $navigation_arrows_position ) {
					STM_LMS_Templates::show_lms_template(
						'elementor-widgets/instructors-carousel/navigation/bottom',
						array(
							'show_navigation'            => $show_navigation,
							'navigation_type'            => $navigation_type,
							'navigation_arrows_position' => $navigation_arrows_position,
							'navigation_arrows_presets'  => $navigation_arrows_presets,
						)
					);
				}
				if ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'side' === $navigation_arrows_position ) {
					?>
					<button class="ms_lms_instructors_carousel__navigation_prev side <?php echo ( ! empty( $navigation_arrows_presets ) ) ? esc_attr( $navigation_arrows_presets ) : 'style_1'; ?>">
						<i class="lnr lnr-chevron-left"></i>
					</button>
					<?php
				}
				?>
				<div class="ms_lms_instructors_carousel__content">
					<div class="swiper-wrapper">
						<?php
						foreach ( $instructors as $instructor ) {
							$user_profile_url = STM_LMS_User::user_public_page_url( $instructor->ID );
							$user             = STM_LMS_User::get_current_user( $instructor->ID, false, true );
							$rating           = STM_LMS_Instructor::my_rating_v2( $user );
							?>
							<div class="ms_lms_instructors_carousel__item swiper-slide <?php echo ( ! empty( $instructor_card_presets ) ) ? esc_attr( $instructor_card_presets ) : 'style_1'; ?>">
								<div class="ms_lms_instructors_carousel__item_wrapper">
									<a href="<?php echo esc_url( $user_profile_url ); ?>" class="ms_lms_instructors_carousel__item_link"></a>
									<?php if ( ! empty( $show_avatars ) && ! empty( $user['avatar_url'] ) ) { ?>
										<div class="ms_lms_instructors_carousel__item_avatar">
											<?php
											if ( ! empty( $show_socials ) && ! empty( $instructor_card_presets ) && 'style_5' === $instructor_card_presets ) {
												STM_LMS_Templates::show_lms_template(
													'elementor-widgets/instructors-carousel/instructor/socials-inside',
													array(
														'show_socials'            => $show_socials,
														'instructor_card_presets' => $instructor_card_presets,
														'socials_presets'         => $socials_presets,
														'user'                    => $user,
													)
												);
											}
											?>
											<a href="<?php echo esc_url( $user_profile_url ); ?>" class="ms_lms_instructors_carousel__item_avatar_link">
												<img src="<?php echo esc_url( $user['avatar_url'] ); ?>" class="ms_lms_instructors_carousel__item_avatar_img">
											</a>
										</div>
									<?php } ?>
									<a href="<?php echo esc_url( $user_profile_url ); ?>" class="ms_lms_instructors_carousel__item_info">
										<h3 class="ms_lms_instructors_carousel__item_title"><?php echo esc_attr( $user['login'] ); ?></h3>
										<?php
										if ( ! empty( $show_instructor_position ) && ! empty( $user['meta']['position'] ) ) {
											STM_LMS_Templates::show_lms_template(
												'elementor-widgets/instructors-carousel/instructor/position',
												array(
													'show_instructor_position' => $show_instructor_position,
													'user'                     => $user,
												)
											);
										}
										if ( ! empty( $show_instructor_course_quantity ) && ! empty( $instructor->course_quantity ) ) {
											STM_LMS_Templates::show_lms_template(
												'elementor-widgets/instructors-carousel/instructor/courses',
												array(
													'show_instructor_course_quantity' => $show_instructor_position,
													'instructor'                      => $instructor,
												)
											);
										}
										if ( ! empty( $show_reviews ) && ! empty( $rating['total'] ) ) {
											STM_LMS_Templates::show_lms_template(
												'elementor-widgets/instructors-carousel/instructor/reviews',
												array(
													'show_reviews'       => $show_reviews,
													'rating'             => $rating,
													'show_reviews_count' => $show_reviews_count,
												)
											);
										}
										?>
									</a>
									<?php
									if ( ! empty( $show_socials ) && ! empty( $instructor_card_presets ) && 'style_5' !== $instructor_card_presets ) {
										STM_LMS_Templates::show_lms_template(
											'elementor-widgets/instructors-carousel/instructor/socials',
											array(
												'show_socials'            => $show_socials,
												'instructor_card_presets' => $instructor_card_presets,
												'socials_presets'         => $socials_presets,
												'user'                    => $user,
											)
										);
									}
									?>
								</div>
							</div>
					<?php } ?>
					</div>
				</div>
				<?php
				if ( ! empty( $show_navigation ) && 'arrows' === $navigation_type && 'side' === $navigation_arrows_position ) {
					?>
					<button class="ms_lms_instructors_carousel__navigation_next side <?php echo ( ! empty( $navigation_arrows_presets ) ) ? esc_attr( $navigation_arrows_presets ) : 'style_1'; ?>">
						<i class="lnr lnr-chevron-right"></i>
					</button>
					<?php
				}
				?>
			</div>
		<?php } else { ?>
			<p class="ms_lms_instructors_carousel__no_results"><?php echo esc_html_e( 'No instructors found', 'masterstudy-lms-learning-management-system' ); ?></p>
		<?php } ?>
	</div>
</div>
