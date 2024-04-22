<?php

use MasterStudy\Lms\Plugin\Addons;

$banner = Addons::list()[ $addon ];
?>
<div class="masterstudy-lms-unlock-addons-wrapper">
	<div class="unlock-addons-inner-wrapper">
		<div class="unlock-wrapper-content">
			<h2>
				<?php echo esc_html__( 'Unlock', 'masterstudy-lms-learning-management-system' ); ?>
				<span class="unlock-addon-name">
					<?php echo esc_html( $banner['name'] ?? '' ); ?>
				</span>
				<?php echo esc_html__( 'addon', 'masterstudy-lms-learning-management-system' ); ?>
				<div class="unlock-pro-logo-wrapper">
					<?php echo esc_html__( 'with', 'masterstudy-lms-learning-management-system' ); ?>&nbsp;
					<span class="unlock-pro-logo">
						<?php echo esc_html__( ' MasterStudy', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pro-features/unlock-pro-logo.svg' ); ?>">
				</div>
			</h2>
			<p><?php echo esc_html( ( $banner['description'] ?? 'default' ) ); ?> </p>
			<div class="unlock-addons-buttons">
				<a href="<?php echo esc_url( admin_url( "admin.php?page=stm-lms-go-pro&source={$banner['documentation']}" ) ); ?>" target="_blank" class="primary button btn">
					<?php echo esc_html__( 'Upgrade to PRO', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
				<a href="<?php echo esc_url( masterstudy_lms_addons_dynamic_url( $banner['documentation'] ) ); ?>" target="_blank" class="secondary button btn">
					<?php echo esc_html__( 'Learn more', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</div>
		</div>
		<div class="unlock-wrapper-illustration">
			<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/pro-features/addons/' . ( $banner['documentation'] ?? 'default' ) . '.png' ); ?>">
		</div>
	</div>
</div>
