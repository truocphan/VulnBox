<?php

function mo_openid_doc_tab() {
	wp_enqueue_style( 'mo_openid_style_dt', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/css/bootstrap.min.css' );
	wp_enqueue_script( 'mo_openid_plugins_dt_bootstrap_js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/js/mo-openid-bootstrap.min.js' );
	wp_enqueue_script( 'mo_openid_plugins_dt_jquery_js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/js/mo-openid-jquery.min.js' );
	?>

	<head>
		<script type="text/javascript" src="http://www.youtube.com/player_api"></script>
	</head>
	<style>

		#app_modal_dialogue {
			position:  fixed;
			width: 900px;
			top: 40px;
			left: calc(50% - 450px);
			bottom: 40px;
			z-index: 100;
		}

		#app_modal_body {
			height: 306px;
			overflow-y: auto;
		}

		@media (min-height: 500px) {
			#app_modal_body { height: 400px; }
		}

		@media (min-height: 800px) {
			#app_modal_body { height: 600px; }
		}


		.mo-modal-body {
			height: 306px;
			overflow-y: auto;
		}

		@media (min-height: 500px) {
			.mo-modal-body { height: 400px; }
		}

		@media (min-height: 800px) {
			.mo-modal-body { height: 600px; }
		}

		h5 {
			display: flex;
			flex-direction: row;
			letter-spacing: 2.5px;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			padding: 3px;
		}

		h5:before,
		h5:after {
			content: "";
			flex: 1 1;
			border-bottom: 2px solid #000;
			margin: auto;

		}
	</style>

	<div class="modt-container">
		<section class="modt-main-panel">
			<header class="modt-panel-header">
				<h1 class="modt-panel-title">
					Social Login Documentation
					<hr style="border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc);">
				</h1>
			</header>
			<div class="modt-panel-content">
				<div class="modt-leftbox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_app_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_apps.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						Applications
					</footer>
				</div>

				<div class="modt-middlebox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_wc_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_wc_new.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						WooCommerce
					</footer>
				</div>
				<div class="modt-rightbox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_bp_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_bp.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						BuddyPress
					</footer>
				</div>
				<div class="modt-leftbox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_pmpro_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_pmpro.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						Paid Memberships Pro
					</footer>
				</div>

				<div class="modt-middlebox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_mc_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_mc.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						MailChimp
					</footer>
				</div>
				<div class="modt-rightbox">
					<div class="modt-imagedropshadow">
						<img data-toggle="modal" data-target="#modt_sc_modal" width="350px" height="220px" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/modt_sc.png" alt="doc tab wc"/>
					</div>
					<footer class="modt-footer">
						Set up Social Login
					</footer>
				</div>
			</div>



		</section>
	</div>

	<!--  Div for Modal  -->
	<div class="modal fade" id="modt_app_modal" tabindex="-1" role="dialog" aria-labelledby="modt_app_modal" aria-hidden="true">
		<div id="app_modal_dialogue" class="modal-dialog modal-dialog-scrollable">
			<div class="mo-modal-content" style="width: 900px;">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">Applications</h4>
				</div>
				<div id="app_modal_body" class="mo-modal-body">
					<div class="modt-table-container">
						<ul class="modt-table">
							<li class="modt-table-header">
								<div class="modt-table-col modt-table-col-1" style="margin-top: 0px">Sr. No.</div>
								<div class="modt-table-col modt-table-col-2" style="margin-top: 0px">App Name</div>
								<div class="modt-table-col modt-table-col-3" style="margin-top: 0px">Guide</div>
								<div class="modt-table-col modt-table-col-4" style="margin-top: 0px">Video</div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">1</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-amazon" style="margin-bottom: 5px; font-size: 3.5em;color:#FF9900"></i>
									<br>Amazon
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-amazon-with-social-login-in-wordpress" target="_blank">Amazon Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-am" width="150" height="75" src="https://www.youtube.com/embed/yMjufls41dg" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>
								</div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">2</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-facebook" style="margin-bottom: 5px; font-size: 3.5em;color: #1877F2"></i>
									<br>Facebook
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-facebook-social-login-in-wordpress" target="_blank">Facebook Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-fb" width="150" height="75" src="https://www.youtube.com/embed/1uZFF_YdOdU" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">3</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-google" style="margin-bottom: 5px; font-size: 3.5em;color: #DB4437"></i>
									<br>Google
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/login-with-google-using-wordpress-social-login" target="_blank">Google Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-go" width="150" height="75" src="https://www.youtube.com/embed/oYFz5P25org" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">4</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-discord" style="margin-bottom: 5px; font-size: 3.5em;color: #7289da"></i>
									<br>Discord
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-discord-with-social-login-in-wordpress" target="_blank">Discord Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-dc" width="150" height="75" src="https://www.youtube.com/embed/zryQ0xE5sKA" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">5</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-linkedin" style="margin-bottom: 5px; font-size: 3.5em;color: #007bb6"></i>
									<br>LinkedIn</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-linkedin-with-social-login-in-wordpress" target="_blank">LinkedIn Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-dc" width="150" height="75" src="https://www.youtube.com/embed/Qs-PSyy7KVQ" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">6</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-twitter" style="margin-bottom: 5px; font-size: 3.5em;color: #2795e9"></i>
									<br>Twitter
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-twitter-with-social-login-in-wordpress" target="_blank">Twitter Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-tw" width="150" height="75" src="https://www.youtube.com/embed/qJmjBQyUBKU" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">8</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-yahoo" style="margin-bottom: 5px; font-size: 3.5em;color: #430297"></i>
									<br>Yahoo</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/guide-to-configure-yahoo-social-login-in-wordpress" target="_blank">Yahoo Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">9</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-vk" style="margin-bottom: 5px; font-size: 3.5em;color: #4C75A3"></i>
									<br>Vkontakte
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-vkontakte-with-social-login-in-wordpress" target="_blank">Vkontakte Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-wl" width="150" height="75" src="https://www.youtube.com/embed/LqYZIKKEVxY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">10</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-snapchat" style="margin-bottom: 5px; font-size: 3.5em;color: #FFFC00"></i>
									<br>Snapchat
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-snapchat-with-social-login-in-wordpress" target="_blank">Snapchat Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming Soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">10</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-dribbble" style="margin-bottom: 5px; font-size: 3.5em;color: ##ff3399"></i>
									<br>Dribbble
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-dribbble-with-social-login-in-wordpress" target="_blank">Snapchat Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-wl" width="150" height="75" src="https://www.youtube.com/embed/9M95pxJ8Emo" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>
								</div>
							</li>

							<br><h5>PREMIUM APPLICATIONS</h5><br>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">8</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-apple" style="margin-bottom: 5px; font-size: 3.5em;color: black"></i>
									<br>Apple
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-apple-with-social-login-in-wordpress" target="_blank">Apple Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-ap" width="150" height="75" src="https://www.youtube.com/embed/-8FGxHGQV2Y" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">10</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/icons/disqus.png" alt="disqus" style="margin-bottom: 5px"/>
									<br>Disqus
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-disqus-with-social-login-in-wordpress" target="_blank">Disqus Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-dq" width="150" height="75" src="https://www.youtube.com/embed/fusUWinPYXY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">12</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-flickr" style="margin-bottom: 5px; font-size: 3.5em;color: #ff0084"></i>
									<br>Flickr
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-flickr-with-social-login-in-wordpress" target="_blank">Flickr Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-fr" width="150" height="75" src="https://www.youtube.com/embed/in6qZxepwow" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">13</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-foursquare" style="margin-bottom: 5px; font-size: 3.5em;color: #f94877"></i>
									<br>Foursquare
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-to-foursquare-with-social-login-in-wordpress" target="_blank">Foursquare Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">14</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/icons/kakao.png" alt="kakao" style="margin-bottom: 5px"/>
									<br>Kakao
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/login-with-kakao-using-wordpress-social-login" target="_blank">Kakao Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">15</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-line" style="margin-bottom: 5px; font-size: 3.5em;color: #00c300"></i>
									<br>Line
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-line-with-social-login-in-wordpress" target="_blank">Line Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-ln" width="150" height="75" src="https://www.youtube.com/embed/9uVn-y-ov7o" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">16</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-meetup" style="margin-bottom: 5px; font-size: 3.5em;color: #e51937"></i>
									<br>Meetup
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-meetup-with-social-login-in-wordpress" target="_blank">Meetup Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-mu" width="150" height="75" src="https://www.youtube.com/embed/RSx5zvgj56U" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">17</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/icons/naver.png" alt="naver" style="margin-bottom: 5px"/>
									<br>Naver
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-naver-with-social-login-in-wordpress" target="_blank">Naver Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">18</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-odnoklassniki" style="margin-bottom: 5px; font-size: 3.5em;color: #f97400"></i>
									<br>Odnoklassniki
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-odnoklassniki-with-social-login-in-wordpress" target="_blank">Odnoklassniki Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">19</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-paypal" style="margin-bottom: 5px; font-size: 3.5em;color: #0d127a"></i>
									<br>Paypal
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/login-with-paypal-using-wordpress-social-login" target="_blank">Paypal Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">20</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-pinterest" style="margin-bottom: 5px; font-size: 3.5em;color: #cb2027"></i>
									<br>Pinterest
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-pinterest-with-social-login-in-wordpress" target="_blank">Pinterest Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-pi" width="150" height="75" src="https://www.youtube.com/embed/jVrYbMJvlaY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">21</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-reddit" style="margin-bottom: 5px; font-size: 3.5em;color: #ff4301"></i>
									<br>Reddit
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-reddit-with-social-login-in-wordpress" target="_blank">Reddit Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">23</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-spotify" style="margin-bottom: 5px; font-size: 3.5em;color: #19bf61"></i>
									<br>Spotify
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-spotify-with-social-login-in-wordpress" target="_blank">Spotify Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-st" width="150" height="75" src="https://www.youtube.com/embed/hhkcENzW3B4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">24</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-stack-exchange" style="margin-bottom: 5px; font-size: 3.5em;color: #0000ff"></i>
									<br>Stackexchange
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-stackexchange-with-social-login-in-wordpress" target="_blank">Stackexchange Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-se" width="150" height="75" src="https://www.youtube.com/embed/hhkcENzW3B4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">25</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/icons/teamsnap.png" alt="teamsnap" style="margin-bottom: 5px"/>
									<br>Teamsnap
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-teamsnap-with-social-login-in-wordpress" target="_blank">Teamsnap Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">26</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-tumblr" style="margin-bottom: 5px; font-size: 3.5em;color: #2c4762"></i>
									<br>Tumblr
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-tumblr-with-social-login-in-wordpress" target="_blank">Tumblr Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-tl" width="150" height="75" src="https://www.youtube.com/embed/43-dk46bPkw" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">27</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-twitch" style="margin-bottom: 5px; font-size: 3.5em;color: #720e9e"></i>
									<br>Twitch
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="http://plugins.miniorange.com/configure-twitch-with-social-login-in-wordpress" target="_blank">Twitch Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-th" width="150" height="75" src="https://www.youtube.com/embed/dS77fqT5IpM" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">28</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-vimeo" style="margin-bottom: 5px; font-size: 3.5em;color: #1ab7ea"></i>
									<br>Vimeo
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-vimeo-with-social-login-in-wordpress" target="_blank">Vimeo Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><iframe id="modt-vid-vi" width="150" height="75" src="https://www.youtube.com/embed/v-dEQno1Z98" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">29</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-weixin" style="margin-bottom: 5px; font-size: 3.5em;color: #00c300"></i>
									<br>Wechat
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/setup-wechat-social-login-to-your-wordpress-website" target="_blank">Wechat Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">30</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-wordpress" style="margin-bottom: 5px; font-size: 3.5em;color: #587ea3"></i>
									<br>WordPress
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-wordpress-with-social-login-wordpress" target="_blank">WordPress Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">31</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-yandex" style="margin-bottom: 5px; font-size: 3.5em;color: #FF0000"></i>
									<br>Yandex
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/login-with-yandex-using-wordpress-social-login" target="_blank">Yandex Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">32</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-github" style="margin-bottom: 5px; font-size: 3.5em;color: #000000"></i>
									<br>Github
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/integrate-github-login-in-your-website-wordpress-oauth" target="_blank">Github Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">33</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-hubspot" style="margin-bottom: 5px; font-size: 3.5em;color: #f57722"></i>
									<br>Hubspot
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-hubspot-sign-in-for-wordpress-website" target="_blank">Hubspot Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">34</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fas fa-at" style="margin-bottom: 5px; font-size: 3.5em;color: #ffa930"></i>
									<br>Mail.ru
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/configure-social-login-into-wordpress-using-mail-ru-mail-ru-sso" target="_blank">Mail.ru Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							 <li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">35</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-steam" style="margin-bottom: 5px; font-size: 3.5em;color: #00adee"></i>
									<br>Steam
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/how-to-setup-steam-login-for-wordpress-website-social-login" target="_blank">Steam Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							<li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">36</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-dropbox" style="margin-bottom: 5px; font-size: 3.5em;color: #3d9ae8"></i>
									<br>Dropbox
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/login-with-dropbox-on-wordpress-using-social-login" target="_blank">Dropbox Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

							 <li class="modt-table-row">
								<div class="modt-table-col modt-table-col-1" data-label="Sr. No.">37</div>
								<div class="modt-table-col modt-table-col-2" data-label="App Name">
									<i class="fab fa-weibo" style="margin-bottom: 5px; font-size: 3.5em;color: #ce1126"></i>
									<br>Weibo
								</div>
								<div class="modt-table-col modt-table-col-3" data-label="Guide"><a href="https://plugins.miniorange.com/guide-to-configure-weibo-social-login-into-wordpress-weibo-sso" target="_blank">Weibo Login Setup Guide</a></div>
								<div class="modt-table-col modt-table-col-4" data-label="Video"><p>Coming soon!</p></div>
							</li>

						</ul>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modt_wc_modal" tabindex="-1" role="dialog" aria-labelledby="modt_wc_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable"  style="padding-top: 70px; width: 700px">
			<div class="mo-modal-content">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">WooCommerce Integration</h4>
				</div>
				<div class="mo-modal-body" style="margin-left: 8%;">
					Make buying from your store easier with the help of Social Login.
					Allow users to login to your Website using popular social applications such as Facebook, Google, Twitter and many more!
					<br><br><strong>Features : </strong>
					Social Login icons on WooCommerce Login, Registration and Checkout pages. WooCommerce Integration - user details are pre-filled on Checkout.
					<br><br>
					<strong>Guide : </strong><a href="https://plugins.miniorange.com/guide-to-configure-woocommerce-with-wordpress-social-login" target="_blank">WooCommerce Social Login Options and Integration</a>
					<br><br>WooCommerce Integration Step by Step Video Guide:
					<br><br><iframe id="modt-vid-wc" width="550" height="306" src="https://www.youtube.com/embed/M20AR-wbKNI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modt_bp_modal" tabindex="-1" role="dialog" aria-labelledby="modt_bp_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable"  style="padding-top: 70px; width: 700px">
			<div class="mo-modal-content">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">BuddyPress Integration</h4>
				</div>
				<div class="mo-modal-body" style="margin-left: 8%;">
					With BuddyPress your users can enjoy the benefits of social networking on your website.
					Inspire comradery on your website by enabling social login with Facebook, Google and many more apps.
					<br><br><strong>Features : </strong>
					Social Login icons on BuddyPress Registration and User Account Details pages. WooCommerce Integration - user details are pre-filled on Checkout.
					<br><br>
					<strong>Guide : </strong><a href="https://plugins.miniorange.com/guide-to-configure-buddypress-with-wordpress-social-login" target="_blank">BuddyPress Social Login Options and Integration</a>
					<br><br>BuddyPress Integration Step by Step Video Guide:
					<br><br><iframe id="modt-vid-bp" width="550" height="306" src="https://www.youtube.com/embed/Iia1skKRYBU" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modt_pmpro_modal" tabindex="-1" role="dialog" aria-labelledby="modt_pmpro_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable"  style="padding-top: 70px; width: 700px">
			<div class="mo-modal-content">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">Paid Memberships Pro Integration</h4>
				</div>
				<div class="mo-modal-body" style="margin-left: 8%;">
					Enable memberships on your website using the widely popular Paid Memberships Pro plugin.
					Users can immediately become the members of your site and select the level of membership they want.
					Make this process even simpler using Social Login.
					Assign default membership levels or let users select a level when they register with the PMPro Integration.
					<br><br><strong>Features : </strong>
					Social Login icons on Paid Memberships Pro Checkout page. Paid Memberships Pro Integration.
					<br><br>
					<strong>Guide : </strong><a href="https://plugins.miniorange.com/guide-to-configure-paid-membership-pro-with-wordpress-social-login" target="_blank">Paid Memberships Pro Social Login Options and Integration</a>
					<br><br>Paid Memberships Pro Integration Step by Step Video Guide:
					<br><br><iframe id="modt-vid-pm" width="550" height="306" src="https://www.youtube.com/embed/DHgIR6kyX3A" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modt_mc_modal" tabindex="-1" role="dialog" aria-labelledby="modt_mc_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable"  style="padding-top: 70px; width: 700px">
			<div class="mo-modal-content">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">Mailchimp Integration</h4>
				</div>
				<div class="mo-modal-body" style="margin-left: 8%;">

					Automate marketing and enable email marketing services with mailchimp.
					With MailChimp integration a user is added as a subscriber to a mailing list in MailChimp when that user registers using social login. First name, last name and email are also captured for that user in the Mailing List.
					<br><br><strong>MailChimp Integration : </strong>
					Email-Ids of Users logging in through Social Login icons will be saved in MailChimp Contact List.
					<br><br>
					<strong>Guide : </strong><a href="https://plugins.miniorange.com/guide-to-configure-mailchimp-integration-with-wordpress-social-login" target="_blank">MailChimp Integration</a>
					<br><br>MailChimp Integration Step by Step Video Guide:
					<br><br><iframe id="modt-vid-mc" width="550" height="306" src="https://www.youtube.com/embed/3Zh5gUX0O_A" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modt_sc_modal" tabindex="-1" role="dialog" aria-labelledby="modt_sc_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable"  style="padding-top: 70px; width: 700px">
			<div class="mo-modal-content">
				<div class="mo-modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="mo-modal-title">Set up Social Login</h4>
				</div>
				<div class="mo-modal-body" style="margin-left: 8%;">
					Confused? Need help in setting up the Social Login plugin?
					Please refer to the linked guide and video below.
					<br>If you still have issues then contact us using the 'Need Help' button on the right side of your screen.
					We offer 24/7 support and we are always here to help out with anything you need.
					<br><br>
					<strong>Guide : </strong><a href="https://plugins.miniorange.com/configure-miniorange-social-login-plugin-setup-in-wordpress" target="_blank">Social Login Set Up Guide</a>
					<br><br>Social Login Step by Step Video Guide:
					<br><br><iframe id="modt-vid-sc" width="550" height="306" src="https://www.youtube.com/embed/ln17jan6t1Y" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>

		jQuery('#modt_app_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-am,#modt-vid-fb,#modt-vid-go,#modt-vid-tw,#modt-vid-vk,#modt-vid-wl,#modt-vid-ap,#modt-vid-dc,#modt-vid-dq,#modt-vid-dr,#modt-vid-fr,#modt-vid-ln,#modt-vid-mu,#modt-vid-pi,#modt-vid-st,#modt-vid-sc,#modt-vid-se,#modt-vid-tl,#modt-vid-th').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-am,#modt-vid-fb,#modt-vid-go,#modt-vid-tw,#modt-vid-vk,#modt-vid-wl,#modt-vid-ap,#modt-vid-dc,#modt-vid-dq,#modt-vid-dr,#modt-vid-fr,#modt-vid-ln,#modt-vid-mu,#modt-vid-pi,#modt-vid-st,#modt-vid-sc,#modt-vid-se,#modt-vid-tl,#modt-vid-th').prop('src','');
			jQuery('#modt-vid-am,#modt-vid-fb,#modt-vid-go,#modt-vid-tw,#modt-vid-vk,#modt-vid-wl,#modt-vid-ap,#modt-vid-dc,#modt-vid-dq,#modt-vid-dr,#modt-vid-fr,#modt-vid-ln,#modt-vid-mu,#modt-vid-pi,#modt-vid-st,#modt-vid-sc,#modt-vid-se,#modt-vid-tl,#modt-vid-th').prop('src',videoURL);
		})

		jQuery('#modt_wc_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-wc').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-wc').prop('src','');
			jQuery('#modt-vid-wc').prop('src',videoURL);
		})

		jQuery('#modt_bp_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-bp').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-bp').prop('src','');
			jQuery('#modt-vid-bp').prop('src',videoURL);
		})

		jQuery('#modt_pmpro_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-pm').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-pm').prop('src','');
			jQuery('#modt-vid-pm').prop('src',videoURL);
		})

		jQuery('#modt_mc_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-mc').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-mc').prop('src','');
			jQuery('#modt-vid-mc').prop('src',videoURL);
		})

		jQuery('#modt_sc_modal').on('hidden.bs.modal', function () {
			var videoURL = jQuery('#modt-vid-sc').prop('src');
			videoURL = videoURL.replace("&autoplay=1", "");
			jQuery('#modt-vid-sc').prop('src','');
			jQuery('#modt-vid-sc').prop('src',videoURL);
		})

	</script>

	<?php
}
