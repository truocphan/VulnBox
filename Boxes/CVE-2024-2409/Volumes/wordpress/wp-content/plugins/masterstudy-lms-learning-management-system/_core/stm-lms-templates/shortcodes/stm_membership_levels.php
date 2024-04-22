<?php
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels      = pmpro_getAllLevels( false, true );
$pmpro_level_order = pmpro_getOption( 'level_order' );

if ( ! empty( $pmpro_level_order ) ) {
	$pmpro_order = explode( ',', $pmpro_level_order );
	array_multisort( $pmpro_order, SORT_ASC, $pmpro_levels );
}

$pmpro_levels = apply_filters( 'pmpro_levels_array', $pmpro_levels );
?>

<div class="stm_lms_levels__head">
	<h1 class="stm_lms_levels__head_title"><?php esc_html_e( 'Membership plans', 'masterstudy-lms-learning-management-system' ); ?></h1>
</div>

<div class="stm_lms_levels__wrapper">
	<?php
	$count = 0;
	foreach ( $pmpro_levels as $level_number => $level ) {
		$current_level    = isset( $current_user->membership_level->ID ) ? $current_user->membership_level->ID === $level->id : false;
		$courses_included = get_option( "stm_lms_course_number_{$level->id}" );
		$featured_quotas  = get_option( "stm_lms_featured_courses_number_{$level->id}" );

		if ( empty( $current_user->membership_level->ID ) || ! $current_level ) {
			$text = esc_html__( 'Get started', 'masterstudy-lms-learning-management-system' );
			$url  = pmpro_url( 'checkout', '?level=' . $level->id, 'https' );
		} elseif ( $current_level ) {
			if ( pmpro_isLevelExpiringSoon( $current_user->membership_level ) && $current_user->membership_level->allow_signups ) {
				$text = esc_html__( 'Renew', 'masterstudy-lms-learning-management-system' );
				$url  = pmpro_url( 'checkout', '?level=' . $level->id, 'https' );
			} else {
				$text = esc_html__( 'Your Level', 'masterstudy-lms-learning-management-system' );
				$url  = esc_url( STM_LMS_User::my_pmpro_url() );
			}
		}

		$level_price             = ( pmpro_isLevelFree( $level ) ) ? esc_html__( 'Free', 'masterstudy-lms-learning-management-system' ) : pmpro_formatPrice( $level->initial_payment );
		$level_price_description = pmpro_getLevelCost( $level );
		$level_period            = pmpro_translate_billing_period( $level->cycle_period );
		$level_period_count      = ( 1 < $level->cycle_number ) ? $level_period . 's' : $level_period;
		?>

		<div class="stm_lms_levels__container">
			<div class="stm_lms_levels <?php echo ( isset( $css_plan_container ) ) ? esc_attr( $css_plan_container ) : ''; ?>">
				<?php
				if ( isset( $level_mark_list ) && ! empty( $level_mark_list ) ) {
					foreach ( $level_mark_list as $item ) {
						if ( $level->name === $item['level_mark_relation'] ) {
							?>
							<div class="stm_lms_levels__mark <?php echo esc_attr( $item['level_mark_position'] ); ?>">
								<h3 class="stm_lms_levels__mark_title elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
									<span><?php echo esc_html( $item['level_mark_title'] ); ?></span>
								</h3>
							</div>
							<?php
						}
					}
				} elseif ( isset( $plan_label ) && ! empty( $plan_label ) ) {
					$plan_labels = vc_param_group_parse_atts( $plan_label );
					if ( ! empty( $plan_labels ) ) {
						foreach ( $plan_labels as $item ) {
							if ( $level->name === $item['plan_label_relation'] ) {
								if ( isset( $item['plan_title'] ) && ! empty( $item['plan_title'] ) ) {
									?>
									<div class="stm_lms_levels__mark">
										<h3 class="stm_lms_levels__mark_title">
											<span><?php echo esc_html( $item['plan_title'] ); ?></span>
										</h3>
									</div>
									<?php
								}
							}
						}
					}
				}
				?>
				<div class="stm_lms_levels__name <?php echo ( ( isset( $level_mark_list ) && ! empty( $level_mark_list ) ) || ( isset( $plan_labels ) && ! empty( $plan_labels ) ) ) ? '' : 'standard_padding'; ?>">
					<h2 class="stm_lms_levels__name_title">
						<?php echo esc_html( $level->name ); ?>
						<?php
						if ( pmpro_isLevelRecurring( $level ) ) {
							?>
							<span class="stm_lms_levels__name_period">
								<?php
								if ( 1 < $level->cycle_number ) {
									echo sprintf( wp_kses_post( __( ' / per %1$d %2$s*', 'masterstudy-lms-learning-management-system' ) ), esc_html( $level->cycle_number ), esc_html( $level_period_count ) );
								} else {
									echo sprintf( wp_kses_post( __( ' / per %s*', 'masterstudy-lms-learning-management-system' ) ), esc_html( $level_period_count ) );
								}
								?>
							</span>
						<?php } ?>
					</h2>
				</div>
				<div class="stm_lms_levels__price">
					<h3 class="stm_lms_levels__price_value price_<?php echo esc_attr( $level_price ); ?>"><?php echo wp_kses_post( $level_price ); ?></h3>
				</div>
				<?php
				if ( pmpro_isLevelRecurring( $level ) || $level->expiration_period ) {
					?>
					<div class="stm_lms_levels__price_description">
						<?php
						if ( pmpro_isLevelRecurring( $level ) ) {
							echo '* ' . wp_kses_post( $level_price_description );
						}
						if ( $level->expiration_period ) {
							?>
							<span>
							<?php
								esc_html_e( 'Expires after: ', 'masterstudy-lms-learning-management-system' );
								echo sprintf(
									wp_kses_post( '%1$d %2$s', 'masterstudy-lms-learning-management-system' ),
									esc_html( $level->expiration_number ),
									wp_kses_post( pmpro_translate_billing_period( $level->expiration_period, $level->expiration_number ) )
								);
							?>
							</span>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( ! empty( $level->description ) ) { ?>
					<div class="stm_lms_levels__description">
						<?php echo wp_kses_post( ent2ncr( $level->description ) ); ?>
					</div>
				<?php } ?>
				<div class="stm_lms_levels__order">
					<div class="stm_lms_levels__button <?php echo esc_attr( $button_position ); ?>">
						<a class="stm_lms_levels__button_element" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_attr( $text ); ?>
						</a>
					</div>
					<div class="stm_lms_levels__items">
						<?php if ( ! empty( $courses_included ) ) { ?>
							<div class="stm_lms_levels__item">
								<span class="stm_lms_levels__items_icon">
									<?php
									if ( isset( $level_items_icons ) && ! empty( $level_items_icons ) ) {
										\Elementor\Icons_Manager::render_icon( $level_items_icons, array( 'aria-hidden' => 'true' ) );
									} else {
										?>
										<i class="fas fa-check-circle"></i>
										<?php
									}
									?>
								</span>
								<?php printf( esc_html__( 'Courses included: %s', 'masterstudy-lms-learning-management-system' ), esc_html( $courses_included ) ); ?>
							</div>
						<?php } ?>
						<?php if ( ! empty( $featured_quotas ) ) { ?>
							<div class="stm_lms_levels__item">
								<span class="stm_lms_levels__items_icon">
									<?php
									if ( isset( $level_items_icons ) && ! empty( $level_items_icons ) ) {
										\Elementor\Icons_Manager::render_icon( $level_items_icons, array( 'aria-hidden' => 'true' ) );
									} else {
										?>
										<i class="fas fa-check-circle"></i>
										<?php
									}
									?>
								</span>
								<?php printf( esc_html__( 'Featured courses quote included: %s', 'masterstudy-lms-learning-management-system' ), esc_html( $featured_quotas ) ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
