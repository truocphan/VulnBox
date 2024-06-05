<?php
	$plugins_list = array(

		array(
			'title' 	=> 'Add-ons you may like',
			'type' 		=> 'section',
			'id' 		=> 'addons',
		),

		array(
			'title' 	=> 'Mobile Login ( OTP ) ',
			'dashicon'  => 'dashicons-smartphone',
			'desc' 		=> 'Allow users to signup/login with OTP.',
			'demo' 		=> 'http://demo.xootix.com/mobile-login-for-woocommerce',
			'download'  => 'https://wordpress.org/plugins/mobile-login-woocommerce/'
		),


		array(
			'title' 	=> 'User Verification',
			'dashicon'  => 'dashicons-email',
			'desc' 		=> 'Send a verification email to user on signup.',
			'demo' 		=> 'http://demo.xootix.com/user-verification-for-woocommerce',
			'pluginpage'=> 'https://xootix.com/plugins/user-verification-for-woocommerce/'
		),


		array(
			'title' 	=> 'Try other awesome plugins',
			'type' 		=> 'section',
			'id' 		=> 'other-plugins',
		),

		array(
			'title' 	=> 'Woo Side Cart',
			'dashicon'  => 'dashicons-cart',
			'desc' 		=> 'Adds a site wide basket icon that displays the added cart items.',
			'demo' 		=> 'http://demo.xootix.com/side-cart-for-woocommerce',
			'download'  => 'https://wordpress.org/plugins/side-cart-woocommerce/'
		),

		array(
			'title' 	=> 'Woo Waitlist',
			'dashicon'  => 'dashicons-list-view',
			'desc' 		=> 'Lets you track demand for out-of-stock items, ensuring your customers feel informed.',
			'demo' 		=> 'http://demo.xootix.com/waitlist-for-woocommerce',
			'download'  => 'https://wordpress.org/plugins/waitlist-woocommerce/'
		),

		array(
			'title' 	=> 'Woo Quick View',
			'dashicon'  => 'dashicons-welcome-view-site',
			'desc' 		=> 'Allow users to get a quick look of products without opening the product page.',
			'demo' 		=> 'http://demo.xootix.com/quick-view-for-woocommerce',
			'download'  => 'https://wordpress.org/plugins/quick-view-woocommerce/'
		),

		array(
			'title' 	=> 'Woo Cart Popup',
			'dashicon'  => 'dashicons-cart',
			'desc' 		=> 'Shows the item added to cart without page refresh.',
			'demo' 		=> 'http://demo.xootix.com/cart-pop-up-for-woocommerce',
			'download'  => 'https://wordpress.org/plugins/added-to-cart-popup-woocommerce/'
		),
	)
?>

<a class="xoo-sidebar-toggle">Hide</a>
<div class="xoo-other-plugins">
	<ul class="xoo-op-list">
		<?php foreach($plugins_list as $plugin): ?>

			<?php if( isset( $plugin['type'] ) && $plugin['type'] === 'section' ): ?>
				<li class="xoo-sidebar-head section-<?php echo esc_attr( $plugin['id'] ); ?>"><?php echo esc_html( $plugin['title'] ); ?></li>
			<?php continue; endif; ?>

				<li class="xoo-op-plugin">
					<div class="xoo-op-plugin-icon">
						<span class="dashicons <?php echo esc_attr( $plugin['dashicon'] ); ?>"></span>
					</div>

					<div class="xoo-op-plugin-details">
						<span class="xoo-op-plugin-head"><?php echo esc_html( $plugin['title'] ); ?></span>
						<span class="xoo-op-plugin-about"><?php echo esc_html( $plugin['desc'] ); ?></span>
						<a href="<?php echo esc_url( $plugin['demo'] ); ?>">Demo</a>
						<?php if(isset( $plugin['download'] )): ?>
							<a href="<?php echo esc_url( $plugin['download'] ); ?>">Download</a>
						<?php endif; ?>
						<?php if( isset( $plugin['pluginpage'] ) ): ?>
							<a href="<?php echo esc_url( $plugin['pluginpage'] ) ?>">Plugin Page</a>
						<?php endif; ?> 
					</div>
				</li>
		<?php endforeach; ?>
	</ul>
</div>
