<?php
use \MasterStudy\Lms\Plugin\Addons;
?>
<div class="stm-lms-addons">
	<div class="stm-lms-addon-search">
		<input id="addons-search" type="text" placeholder="<?php esc_attr_e( 'Search addons', 'masterstudy-lms-learning-management-system' ); ?>"
			value="<?php echo esc_attr( $_GET['search'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
			<defs></defs>
			<path id="Forma_1" data-name="Forma 1" d="M15.8,14.855L11.25,10.31a6.338,6.338,0,1,0-.942.942L14.854,15.8A0.666,0.666,0,1,0,15.8,14.855ZM6.335,11.33a5,5,0,1,1,4.994-5A5,5,0,0,1,6.335,11.33Z" class="cls-1"></path>
		</svg>
	</div>
	<?php
	if ( ! STM_LMS_Helpers::is_pro() ) {
		?>
	<div class="stm-lms-addon-banner">
		<div class="stm-lms-addon-banner__text">
			<h2>
				<strong class="stm-lms-addon-banner__text_primary"><?php echo esc_html__( 'Unlock all addons', 'masterstudy-lms-learning-management-system' ); ?></strong>
				<?php echo esc_html__( 'with', 'masterstudy-lms-learning-management-system' ); ?>
				<strong><?php echo esc_html__( 'MasterStudy Pro!', 'masterstudy-lms-learning-management-system' ); ?></strong>
			</h2>
			<ul>
				<li>
					<div class="stm-lms-addon-banner__wrapper">
						<div class="stm-lms-addon-banner__image">
							<img class="stm-lms-addon-banner__addons" src="<?php echo esc_url( STM_LMS_URL . '/assets/addons/addons.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( '20+ Premium addons', 'masterstudy-lms-learning-management-system' ); ?>
					</div>
				</li>
				<li>
					<div class="stm-lms-addon-banner__wrapper">
						<div class="stm-lms-addon-banner__image">
							<img class="stm-lms-addon-banner__support" src="<?php echo esc_url( STM_LMS_URL . '/assets/addons/support.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Priority ticket support', 'masterstudy-lms-learning-management-system' ); ?>
					</div>
				</li>
				<li>
					<div class="stm-lms-addon-banner__wrapper">
						<div class="stm-lms-addon-banner__image">
							<img class="stm-lms-addon-banner__updates" src="<?php echo esc_url( STM_LMS_URL . '/assets/addons/updates.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Frequent updates', 'masterstudy-lms-learning-management-system' ); ?>
					</div>
				</li>
				<li>
					<div class="stm-lms-addon-banner__wrapper">
						<div class="stm-lms-addon-banner__image">
							<img class="stm-lms-addon-banner__starter" src="<?php echo esc_url( STM_LMS_URL . '/assets/addons/starter_theme.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Starter theme', 'masterstudy-lms-learning-management-system' ); ?>
					</div>
				</li>
			</ul>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=stm-lms-go-pro&source=get-now-button-addons-banner' ) ); ?>" class="stm-lms-addon-banner__button" target="_blank">
				<i class="fas fa-arrow-right"></i>
				<?php echo esc_html__( 'Get Now', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>
	</div>
		<?php
	}

	$is_pro      = STM_LMS_Helpers::is_pro();
	$is_pro_plus = STM_LMS_Helpers::is_pro_plus();

	foreach ( $addons as $key => $addon ) {
		$pro_plus_addon          = isset( $addon['pro_plus'] );
		$plus_off_and_notproplus = $is_pro && ! $pro_plus_addon;
		$plus_off_and_proplus    = ! $is_pro_plus && $pro_plus_addon;
		$plus_on_and_proplus     = $is_pro_plus && $pro_plus_addon;
		$addon_enabled           = ! empty( $enabled_addons[ $key ] );
		$not_email_branding      = Addons::EMAIL_BRANDING !== $key;
		?>
		<div class="stm-lms-addon <?php echo $addon_enabled ? 'active' : ''; ?>">
			<div class="addon-image">
				<img src="<?php echo esc_url( $addon['url'] ); ?>"/>
			</div>
			<div class="addon-install">
				<div class="addon-title">
					<h4 class="addon-name"><?php echo wp_kses( $addon['name'], array() ); ?></h4>
					<?php
					if ( $plus_off_and_notproplus || ( $plus_on_and_proplus && $not_email_branding ) ) {
						if ( ! empty( $addon['settings'] ) ) {
							?>
							<a href="<?php echo esc_url( $addon['settings'] ); ?>" class="addon-settings <?php echo $addon_enabled ? 'active' : ''; ?>" target="_blank">
								<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/global/settings.svg' ); ?>">
							</a>
							<?php
						}
					}
					if ( $plus_off_and_proplus && $is_pro ) {
						?>
						<span class="addon-badge pro-plus"><?php esc_html_e( 'Pro Plus', 'masterstudy-lms-learning-management-system' ); ?></span>
					<?php } ?>
				</div>
				<div class="addon-description">
					<?php echo wp_kses( $addon['description'], array() ); ?>
				</div>
				<div class="addon-settings-wrapper">
					<?php if ( $not_email_branding && ( ! $is_pro || $plus_on_and_proplus || $plus_off_and_notproplus ) ) { ?>
						<div class="addon-checkbox section_2-enable_courses_filter">
							<?php if ( ! $is_pro ) { ?>
								<div class="addon-checkbox__overlay"></div>
							<?php } ?>
							<label class="addon-checkbox__label" data-key="<?php echo esc_attr( $key ); ?>">
								<div class="addon-checkbox__wrapper <?php echo esc_attr( $is_pro && $addon_enabled ? 'addon-checkbox__wrapper_active' : '' ); ?>">
									<div class="addon-checkbox__switcher"></div>
									<input type="checkbox" name="enable_courses_filter" id="section_2-enable_courses_filter">
								</div>
							</label>
							<span class="addon-checkbox__status">
								<?php esc_html_e( 'Enable', 'masterstudy-lms-learning-management-system' ); ?>
							</span>
						</div>
						<?php if ( ! $is_pro ) { ?>
							<div class="addon-checkbox__locked">
								<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/global/locked.svg' ); ?>" class="addon-checkbox__locked-img">
								<div class="addon-checkbox__locked-dropdown">
									<?php esc_html_e( 'This addon available in Pro version', 'masterstudy-lms-learning-management-system' ); ?>
								</div>
							</div>
							<?php
						}
					}
					if ( $plus_off_and_proplus && $is_pro ) {
						?>
						<div class="addon-get-button">
							<a href="https://stylemixthemes.com/wordpress-lms-plugin/pricing/?utm_source=wpadmin-ms&utm_medium=addons&utm_campaign=get-now-addons" target="_blank">
								<?php esc_html_e( 'Get Pro Plus', 'masterstudy-lms-learning-management-system' ); ?>
							</a>
						</div>
						<?php
					}
					if ( ! empty( $addon['documentation'] ) ) {
						?>
						<div class="addon-documentation">
							<a href="https://docs.stylemixthemes.com/masterstudy-lms/lms-pro-addons/<?php echo esc_attr( $addon['documentation'] ); ?>" target="_blank">
								<?php esc_html_e( 'How it works', 'masterstudy-lms-learning-management-system' ); ?>
							</a>
							<i class="stmlms-question"></i>
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
