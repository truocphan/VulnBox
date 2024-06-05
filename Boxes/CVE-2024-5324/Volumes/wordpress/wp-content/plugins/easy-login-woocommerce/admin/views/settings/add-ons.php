<?php

$link = 'https://xootix.com/plugins/easy-login-for-woocommerce#sp-addons';

$addons = array(

	'social_login' => array(
		'title' => 'Social Login',
		'icon' 	=> 'dashicons-facebook',
		'desc' 	=> 'Allow users to login & register using their social accounts ( Facebook & Google ) with a single click.',
		'link' 	=> $link
	),

	'security' => array(
		'title' => 'Security',
		'icon' 	=> 'dashicons-shield-alt',
		'desc' 	=> 'Protect your form from bots using google recaptcha(v2/v3) + Password strength meter + Limit login attempts',
		'link' 	=> $link
	),

	'fields' => array(
		'title' 	=> 'Custom Registration fields',
		'icon' 		=> 'dashicons-plus',
		'desc' 		=> 'Add extra fields to registration form , display them on user profile & myaccount page. (See <a href="'.admin_url('admin.php?page=xoo-el-fields').'" target="__blank">Fields page</a> to know supported field types )',
		'link' 	=> $link,
	),


	'email_verify' => array(
		'title' => 'Email Verification/User approval',
		'icon' 	=> 'dashicons-email',
		'desc' 	=> 'Disable user access to account until email is verified or approved by admin',
		'link' 	=> $link
	),


	'otp_login' => array(
		'title' => 'One time password(OTP) Login',
		'icon' 	=> 'dashicons-phone',
		'desc' 	=> 'Allow users to login with OTP ( sent on their phone or email ) therefore removing the need to remember a password.',
		'link' 	=> $link
	),

);

?>

<div class="xoo-addon-container">
	<?php foreach ( $addons as $id => $data ): ?>
		<div class="xoo-addon">
			<span class="dashicons <?php echo esc_attr( $data['icon'] ); ?>"></span>
			<span class="xoo-ao-title"><?php echo $data['title'] ?></span>
			<div class="xoo-ao-desc"><?php echo $data['desc']; ?></div>
			<div class="xoo-ao-btns">
				<a href="<?php echo esc_url( $data['link'] ) ?>">BUY</a>
				<?php if( isset( $data['demo'] ) ): ?>
					<a href="<?php echo esc_url( $data['demo'] ) ?>">DEMO</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>