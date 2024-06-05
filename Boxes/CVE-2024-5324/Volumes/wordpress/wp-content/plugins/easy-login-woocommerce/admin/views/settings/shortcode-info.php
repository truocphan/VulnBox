<div class="xoo-el-popup-info">

	<h3>How to open popup?</h3>
	<i><b>Please test the front end without signing in so you can see forms and links</b></i>
	<h5>There are 4 ways</h5>
	<ul class="xoo-el-li-info">
		<li>
			<span>Shortcode</span>
			<p>[xoo_el_action] - Please see "Shortcodes" section below to know more about this shortcode</p>
		</li>
		<li>
			<span>Menu Links</span>
			<p>Navigate to your <a href="<?php echo esc_url( admin_url( 'nav-menus.php?xoo_el_nav=true' ) ); ?>">menu</a> page and select links under "Login/Signup popup" section.</p>
		</li>
		<li>
			<span>Anchor Links</span>
			<p>
				Add #login or #register at the end of your URL
				<xmp><a href="wwww.mywebsite.com#login">Login</a></xmp>
			</p>
		</li>
		<li>
			<span>Classes</span>
			<p>
				You can trigger popup using classes. Below are the class names <br>
				Register :- xoo-el-reg-tgr<br>
				Login :- xoo-el-login-tgr
				<xmp><span class="xoo-el-login-tgr">Login</span></xmp>
			</p>
		</li>
	</ul>
</div>


<?php

$shortcodes = array(
	'xoo_el_inline_form' => array(
		'shortcode' => '[xoo_el_inline_form]',
		'desc' 		=> 'Generates an inline login/signup form',
		'example' 	=> '[xoo_el_inline_form pattern="separate" navstyle="tabs" forms="login,register" active="login" ]',
		'atts' 		=> array(
			array(
				'forms',
				'login / register / login,register',
				'login,register',
				'Forms to show'
			),
			array(
				'active',
				'login / register',
				'login',
				'Which form to be displayed first. '
			),
			array(
				'pattern',
				'separate / single',
				xoo_el_helper()->get_general_option( 'm-form-pattern' ),
				'Single pattern only shows one email field and then further navgiates to login/register form.'
			),
			array(
				'navstyle',
				'tabs / links / disable',
				xoo_el_helper()->get_general_option( 'm-nav-pattern' ),
				'Choose a way to switch between login and registration form.'
			),
			array(
				'login_redirect',
				'same (for same page) / www.customURL.com',
				'',
				'Redirect link after login. If value is set, it will override the login redirect setting.'
			),
			array(
				'register_redirect',
				'same (for same page) / www.customURL.com',
				'',
				'Redirect link after register. If value is set, it will override the register redirect setting.'
			)
		)
	),
	'xoo_el_action' => array(
		'shortcode' => '[xoo_el_action]',
		'desc' 		=> 'Creates a link/button to open popup',
		'example' 	=> '[xoo_el_action type="login" display="button" text="Login" change_to="logout" redirect_to="same"]',
		'atts' 		=> array(
			array(
				'type',
				'login / register / lost-password',
				'login',
				'Which form to open on click'
			),
			array(
				'display',
				'button / link',
				'link',
				'Display as a link or button'
			),
			array(
				'text',
				'Custom Text',
				'Login',
				'Button/Link text'
			),
			array(
				'change_to',
				'hide / logout / myaccount / www.customURL.com',
				'logout',
				'After signing in, the link should change into'
			),
			array(
				'change_to_text',
				'Custom Text',
				'Logout',
				'After signing in, the link text should change into'
			),
			array(
				'redirect_to',
				'same (for same page) / www.customURL.com',
				'',
				'Redirect link after user submits a form. If value is set, it will override the redirection on settings page.'
			)
		)
	)
);

return apply_filters( 'xoo_el_shortcode_info_tab', $shortcodes );

?>