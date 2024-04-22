<?php
$inline = '';

if ( ! empty( $title_color ) && ! empty( $uniq ) ) {
	$title_color = esc_attr( $title_color );
	$inline      = ".{$uniq} .stm_lms_instructors_carousel__top h3, .{$uniq} .stm_lms_instructors_carousel__top .h4 {color: {$title_color};}";
}
if ( empty( $style ) ) {
	$style = 'style_1';
}

wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'owl.carousel' );
wp_enqueue_style( 'owl.carousel' );
stm_lms_module_styles( 'instructors_carousel', $style, array(), $inline );
stm_lms_module_scripts( 'instructors_carousel', 'style_1' );

stm_lms_register_style( 'user' );
stm_lms_register_style( 'instructors_grid' );

$args = array(
	'per_row' => esc_attr( $per_row ),
);
if ( empty( $uniq ) ) {
	$uniq = 0;
}
$limit = ( ! empty( $limit ) ) ? intval( $limit ) : 10;

$user_args = array(
	'role'   => STM_LMS_Instructor::role(),
	'number' => $limit,
);

if ( ! empty( $sort ) && 'rating' === $sort ) {
	$sort_args = array(
		'meta_key' => 'average_rating',
		'orderby'  => 'meta_value_num',
		'order'    => 'DESC',
	);

	$user_args = array_merge( $user_args, $sort_args );
}

$user_query = new WP_User_Query( $user_args );
$results    = $user_query->get_results();
?>
<div class="stm_lms_instructors_carousel_wrapper <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
echo esc_attr( $uniq . ' ' . $style );

if ( isset( $prev_next ) && 'disable' === $prev_next ) {
	echo esc_attr( 'no-nav' );
}

// phpcs:ignore Squiz.PHP.EmbeddedPhp
?>">
	<div class="stm_lms_instructors_carousel"
			data-items="<?php echo esc_attr( $per_row ); ?>"
			data-items-md="<?php echo esc_attr( $per_row_md ); ?>"
			data-items-sm="<?php echo esc_attr( $per_row_sm ); ?>"
			data-items-xs="<?php echo esc_attr( $per_row_xs ); ?>"
			data-pagination="<?php echo esc_attr( $pagination ); ?>">
		<?php if ( ! empty( $results ) ) : ?>
			<div class="stm_lms_instructors_carousel__top">

				<?php if ( ! empty( $title ) ) : ?>
					<h3><?php echo wp_kses_post( $title ); ?></h3>
				<?php endif; ?>
				<?php if ( 'style_2' !== $style ) : ?>
					<a href="<?php echo esc_url( STM_LMS_Instructor::get_instructors_url() ); ?>" class="h4">
						<?php esc_html_e( 'View all', 'masterstudy-lms-learning-management-system' ); ?> <i
								class="lnr lnr-arrow-right"></i>
					</a>
				<?php endif; ?>
				<?php if ( 'disable' !== $prev_next && 'style_2' === $style ) : ?>
					<div class="stm_lms_courses_carousel__buttons">
						<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_prev sbc_h sbrc_h">
							<i class="fa fa-chevron-left"></i>
						</div>
						<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_next sbc_h sbrc_h">
							<i class="fa fa-chevron-right"></i>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="stm_lms_instructors__grid">
				<?php
				foreach ( $user_query->get_results() as $user ) :
					$user_profile_url = STM_LMS_User::user_public_page_url( $user->ID );
					$user             = STM_LMS_User::get_current_user( $user->ID, false, true );
					$rating           = STM_LMS_Instructor::my_rating_v2( $user );
					?>
					<div class="stm_lms_instructors__single stm_carousel_glitch">
						<div class="stm_lms_user_side">

							<?php if ( ! empty( $user['avatar'] ) ) : ?>
								<div class="stm-lms-user_avatar">
									<a href="<?php echo esc_url( $user_profile_url ); ?>">
										<?php echo wp_kses_post( $user['avatar'] ); ?>
									</a>
									<?php if ( 'style_2' === $style ) : ?>
										<div class="user_socials">
											<?php if ( ! empty( $user['meta']['twitter'] ) ) : ?>
												<a href="<?php echo esc_url( $user['meta']['twitter'] ); ?>" class="twitter">
													<i class="fab fa-twitter"></i>
												</a>
											<?php endif; ?>
											<?php if ( ! empty( $user['meta']['facebook'] ) ) : ?>
												<a href="<?php echo esc_url( $user['meta']['facebook'] ); ?>" class="facebook">
													<i class="fab fa-facebook-f"></i>
												</a>
											<?php endif; ?>
											<?php if ( ! empty( $user['meta']['instagram'] ) ) : ?>
												<a href="<?php echo esc_url( $user['meta']['instagram'] ); ?>" class="instagram">
													<i class="fab fa-instagram"></i>
												</a>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<a href="<?php echo esc_url( $user_profile_url ); ?>" class="user-name">
								<h3><?php echo esc_attr( $user['login'] ); ?></h3>
							</a>

							<?php if ( ! empty( $user['meta']['position'] ) ) : ?>
								<h5 class="user-position <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
								if ( 'style_2' === $style ) {
									echo esc_attr( 'normal_font' );
								}
								// phpcs:ignore Squiz.PHP.EmbeddedPhp
								?>">
									<?php echo wp_kses_post( $user['meta']['position'] ); ?>
								</h5>
							<?php endif; ?>

							<?php if ( ! empty( $rating['total'] ) && 'style_2' !== $style ) : ?>
								<div class="stm-lms-user_rating">
									<div class="star-rating star-rating__big" style="background-image: url('<?php echo esc_attr( STM_LMS_URL ); ?>/assets/img/staremptyl.svg');">
										<span style="width: <?php echo floatval( $rating['percent'] ); ?>%; background-image: url('<?php echo esc_attr( STM_LMS_URL ); ?>/assets/img/starfull.svg');"></span>
									</div>
									<strong class="rating heading_font"><?php echo floatval( $rating['average'] ); ?></strong>
									<div class="stm-lms-user_rating__total">
										<?php echo wp_kses_post( $rating['total_marks'] ); ?>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( 'disable' !== $prev_next && 'style_2' !== $style ) : ?>
				<div class="stm_lms_courses_carousel__buttons">
					<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_prev sbc_h sbrc_h">
						<i class="fa fa-chevron-left"></i>
					</div>
					<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_next sbc_h sbrc_h">
						<i class="fa fa-chevron-right"></i>
					</div>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="stm_lms_instructors_carousel__top">
				<h3><?php esc_html_e( 'No instructors found', 'masterstudy-lms-learning-management-system' ); ?></h3>
			</div>
		<?php endif; ?>
	</div>
</div>
