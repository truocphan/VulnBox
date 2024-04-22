<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 */

$is_enable   = $field_data['is_enable'] ?? false;
$is_pro_plus = $field_data['is_pro_plus'] ?? false;
$is_pro      = STM_LMS_Helpers::is_pro();

if ( $is_pro && ! $is_enable && ! $is_pro_plus ) {
	return;
}

$version = ( WP_DEBUG ) ? time() : STM_LMS_VERSION;
wp_enqueue_style( 'stm_lms_unlock_addons', STM_LMS_URL . 'assets/css/stm_lms_unlock_addons.css', null, $version );

$label         = $field_data['label'] ?? '';
$img           = $field_data['img'] ?? '';
$description   = $field_data['desc'] ?? '';
$search_addon  = $field_data['search'] ?? '';
$utm_url       = $field_data['utm_url'] ?? '';
$slug          = str_replace( ' ', '-', mb_strtolower( $label ) );
$redirect_link = admin_url( 'admin.php?page=' . ( $is_enable ? "stm-addons&search={$search_addon}" : "stm-lms-go-pro&source=button-{$slug}-settings" ) );
$redirect_link = ! $is_enable && $is_pro_plus && $utm_url && $is_pro ? $utm_url : $redirect_link;
$link_text     = $is_enable ? esc_html__( 'Enable addon', 'masterstudy-lms-learning-management-system' ) : esc_html__( 'Upgrade to PRO', 'masterstudy-lms-learning-management-system' );
?>
<div class="stm-lms-unlock-pro-banner<?php echo esc_attr( $is_enable || ! $is_enable && $is_pro_plus ? ' addon_disabled' : '' ); ?>">
	<div class="stm-lms-unlock-banner-wrapper">
		<?php
		if ( ! empty( $field_data['hint'] ) && 'slider' === $field_data['hint'] ) {
			$image_sources = array(
				STM_LMS_URL . 'assets/img/pro-features/course-format-1.png',
				STM_LMS_URL . 'assets/img/pro-features/course-format-2.png',
				STM_LMS_URL . 'assets/img/pro-features/course-format-3.png',
				STM_LMS_URL . 'assets/img/pro-features/course-format-4.png',
			);
			?>
			<div class="unlock-pro-banner-slider">
				<div class="unlock-slider-container">
					<div class="unlock-slider-slide-window">
						<div class="unlock-slider-slide-holder" id="unlock-slider-slide-holder">
							<?php foreach ( $image_sources as $image_src ) : ?>
								<div class="unlock-slider-slide">
									<img src="<?php echo esc_attr( $image_src ); ?>">
								</div>
							<?php endforeach; ?>
						</div>
						<div class="unlock-slider-slide-nav" id="unlock-slider-slide-nav">
						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="unlock-banner-image">
				<img src="<?php echo esc_url( $img ); ?>">
			</div>
			<?php
		}
		?>
		<div class="unlock-wrapper-content">
			<h2>
				<?php
				if ( isset( $field_data['hint'] ) && 'slider' !== $field_data['hint'] ) {
					echo esc_html( $field_data['hint'] );
				} else {
					echo $is_enable ? esc_html__( 'Enable', 'masterstudy-lms-learning-management-system' ) : esc_html__( 'Unlock', 'masterstudy-lms-learning-management-system' );
				}
				?>
				<span class="unlock-addon-name">
					<?php
					if ( isset( $field_data['hint'] ) && ( 'Enable' === $field_data['hint'] ) ) {
						echo '<br/>';
					}
					echo esc_html( $label );
					?>
				</span>
				<?php
				if ( isset( $field_data['hint'] ) && ( 'Enable' === $field_data['hint'] || 'Automate' === $field_data['hint'] ) ) {
					echo '<br/>';
				}
				echo esc_html__( 'with', 'masterstudy-lms-learning-management-system' );
				?>
				<div class="unlock-pro-logo-wrapper">
					<span class="unlock-pro-logo"><?php echo esc_html__( 'MasterStudy', 'masterstudy-lms-learning-management-system' ); ?></span>
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pro-features/unlock-pro-logo.svg' ); ?>">
				</div>
			</h2>
			<p><?php echo esc_html( $description ); ?> </p>
			<div class="unlock-pro-banner-footer">
				<div class="unlock-addons-buttons">
					<a href="<?php echo esc_url( $redirect_link ); ?>" target="_blank" class="primary button btn">
						<?php echo esc_html( $link_text ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
