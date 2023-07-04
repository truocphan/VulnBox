<?php

function mo_openid_premium_features() {
	?>
	<style>
		* {
			font-family: "Open Sans";
		}
		*,
		*:before,
		*:after {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
		}
		.small-meta {
			font-size: 12px;
		}
		.dim {
			opacity: 0.4;
		}
		.grid-wrapper {
			margin: 0 auto;
			width: 80%;
			padding: 30px;
		}
		.switch {
			position: relative;
			display: inline-block;
			width: 60px;
			height: 25px;
		}

		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
		}

		.slider:before {
			position: absolute;
			content: "";
			height: 15px;
			width: 26px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
		}

		input:checked + .slider {
			background-color: #2196F3;
		}

		input:focus + .slider {
			box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
			-webkit-transform: translateX(26px);
			-ms-transform: translateX(26px);
			transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
			border-radius: 34px;
		}

		.slider.round:before {
			border-radius: 50%;
		}
	</style>
	<div class="grid-wrapper">
		<div class="card-wrapper" title="This feature is available in the Premium versions">
			<label class="switch">
				<input type="checkbox" class="c-card" disabled/>
				<span class="slider"></span>
				<div class="card-content">
					<div class="card-state-icon"></div>
					<label for="admin_pass_verify"></label>
					<h4><?php echo esc_attr( mo_sl( 'Force Admin To Login Using Password' ) ); ?></h4>
					<a style="left: 513%; top:40%; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<p><?php echo esc_attr( ( 'Admin user tries to login using social login then he will need to enter WordPress admin login credentials to login.' ) ); ?></p>
					<p class="small-meta dim"></p>
				</div>
			</label>
		</div>

		<div class="card-wrapper" title="This feature is available in the Premium versions">
			<label class="switch">
				<input type="checkbox" class="c-card" disabled/>
				<span class="slider"></span>
				<div class="card-content">
					<div class="card-state-icon"></div>
					<label for="mo_openid_user_moderation">    </label>
					<h4><?php echo esc_attr( mo_sl( 'User Moderation' ) ); ?></h4>
					<a style="left: 513%; top:40%; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<p><?php echo esc_attr( mo_sl( 'Enable this feature to restrict the access of newly registered users. User created through social login will not be able to access your website until admin will not allow them by activating their accounts else' ) ); ?></p>
					<p class="small-meta dim">[ *<?php echo esc_attr( mo_sl( 'Notice: SMTP should be configured to send activation emails. ' ) ); ?>]
					</p>
				</div>
			</label>
		</div>

		<div class="card-wrapper" title="This feature is available in the Premium versions">
			<label class="switch">
				<input type="checkbox" class="c-card" disabled />
				<span class="slider"></span>
				<div class="card-content">
					<div class="card-state-icon"></div>
					<label for="mo_openid_notification_email"></label>
					<h4><?php echo esc_attr( mo_sl( 'Reset Password' ) ); ?> </h4>
					<a style="left: 513%; top:40%; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<p><?php echo esc_attr( mo_sl( 'Send password reset link to user after registration' ) ); ?></p>
					<p class="small-meta dim">[ *<?php echo esc_attr( esc_attr( mo_sl( 'Notice: SMTP should be configured to send activation emails' ) ) ); ?>. ]</p>
				</div>
			</label>
		</div>

		<div class="card-wrapper" title="This feature is available in the Premium versions">
			<label class="switch">
				<input type="checkbox" class="c-card" disabled />
				<span class="slider roundededges"></span>
				<div class="card-content">
					<div class="card-state-icon"></div>
					<h4><?php echo esc_attr( mo_sl( 'Extended User Attribute' ) ); ?></h4>
					<a style="left: 513%; top:40%; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<p><?php echo esc_attr( mo_sl( 'Mainly the required data(name,lastname,email) is mapped and use after the user gets login. If you want to use more data that is provided from the app you can enable this feature.(The data is depend on app to app)' ) ); ?> </p>
					<p class="small-meta dim"><?php echo esc_attr( mo_sl( 'Custom App of should be set for this feature' ) ); ?></p>
				</div>
			</label>
		</div>

		<div class="card-wrapper" title="This feature is available in the Premium versions">
			<label class="switch">
				<input type="checkbox" class="c-card" disabled/>
				<span class="slider roundededges"></span>
				<div class="card-content">
					<div class="card-state-icon"></div>
					<h4><?php echo esc_attr( mo_sl( 'Redirect to social in a new window' ) ); ?></h4>
					<a style="left: 513%; top:40%; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
					<p><?php echo esc_attr( mo_sl( 'While login with social login. The login page opens in a new tab. After the login process the tab gets closed.' ) ); ?></p>
					<p class="small-meta dim"></p>
				</div>
			</label>
		</div>
	</div>


	<?php
}
