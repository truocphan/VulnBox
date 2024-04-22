<?php
/**
 * @var $user
 */

stm_lms_register_script( 'affiliate_points' );
stm_lms_register_style( 'affiliate_points' );

?>
<div class="affiliate_points heading_font">
	<div class="stm_lms_become_instructor__top">
		<img src="<?php echo esc_url( STM_LMS_URL . '/assets/img/account/affiliate_link.svg' ); ?>"/>
		<h3><?php esc_html_e( 'Affiliate Program', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>
	</div>
	<p><?php esc_html_e( 'Share the link to earn coins. You will get extra coins from specific actions of users, who applied this link. ', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
	<span id="text_to_copy"><?php echo esc_url( add_query_arg( array( 'affiliate_id' => $user['id'] ), get_site_url() ) ); ?></span>
	<span class="affiliate_points__btn">
		<i class="fas fa-clone"></i>
		<span class="text"><?php esc_html_e( 'Copy Affiliate Link', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</span>
	<span class="copied">
		<i class="fa fa-check"></i>
	</span>
</div>
