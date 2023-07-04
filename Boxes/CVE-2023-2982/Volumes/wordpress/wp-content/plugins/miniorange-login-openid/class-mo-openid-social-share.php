<?php
if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
	$http = 'https://';
} else {
	$http = 'http://';
}
$url = $http . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );

	$default_color = array(
		'facebook'      => '#1877F2',
		'google'        => '#DB4437',
		'vkontakte'     => '#466482',
		'twitter'       => '#2795e9',
		'digg'          => '#000000',
		'yahoo'         => '#430297',
		'yandex'        => '#2795e9',
		'instagram'     => '#3f729b',
		'linkedin'      => '#007bb6',
		'pocket'        => '#ee4056',
		'print'         => '#ee4056',
		'whatsapp'      => '#25D366',
		'mail'          => '#787878',
		'amazon'        => '#ff9900',
		'paypal'        => '#0d127a',
		'stumble'       => '#f74425',
		'salesforce'    => '#1ab7ea',
		'apple'         => '#000000',
		'steam'         => '#000000',
		'wordpress'     => '#587ea3',
		'pinterest'     => '#cb2027',
		'spotify'       => '#19bf61',
		'tumblr'        => '#2c4762',
		'twitch'        => '#720e9e',
		'github'        => '#000000',
		'dribbble'      => '#ee66aa',
		'flickr'        => '#ff0084',
		'stackexchange' => '0000ff',
		'snapchat'      => '#fffc00',
		'reddit'        => '#ff4301',
		'odnoklassniki' => '#f97400',
		'foursquare'    => '#f94877',
		'wechat'        => '#00c300',
		'vimeo'         => '#1ab7ea',
		'line'          => '#00c300',
		'hubspot'       => '#fa7820',
		'discord'       => '#7289da',
		'meetup'        => '#e51937',
		'stackexchange' => '#0000FF',
		'wiebo'         => '#df2029',
		'kakao'         => '#ffe812',
		'livejournal'   => '#3c1361',
		'naver'         => '#3EAF0E',
		'teamsnap'      => '#ff9a1a',
	);

	$selected_theme     = esc_attr( get_option( 'mo_openid_share_theme' ) );
	$selected_direction = esc_attr( get_option( 'mo_openid_share_widget_customize_direction' ) );
	$sharingSize        = esc_attr( get_option( 'mo_sharing_icon_custom_size' ) );
	$custom_color       = esc_attr( get_option( 'mo_sharing_icon_custom_color' ) );
	$custom_theme       = esc_attr( get_option( 'mo_openid_share_custom_theme' ) );
	$fontColor          = esc_attr( get_option( 'mo_sharing_icon_custom_font' ) );
	$spaceBetweenIcons  = esc_attr( get_option( 'mo_sharing_icon_space' ) );
	$twitter_username   = esc_attr( get_option( 'mo_openid_share_twitter_username' ) );
	$heading_text       = esc_html( get_option( 'mo_openid_share_widget_customize_text' ) );
	$heading_color      = esc_attr( get_option( 'mo_openid_share_widget_customize_text_color' ) );

	$email_subject = esc_attr( get_option( 'mo_openid_share_email_subject' ) );
	$email_body    = get_option( 'mo_openid_share_email_body' );
	$email_body    = str_replace( '##url##', $url, $email_body );

	$orientation = 'hor';
	if ( $landscape ) {
		if ( $landscape == 'horizontal' ) {
			$orientation = 'hor';
		} elseif ( $landscape == 'vertical' ) {
			$orientation = 'ver';
		}
	} else {
		if ( get_option( 'mo_openid_share_widget_customize_direction_horizontal' ) ) {
			$orientation = 'hor';
		} elseif ( get_option( 'mo_openid_share_widget_customize_direction_vertical' ) ) {
			$orientation = 'ver';
		}
	}
	?>
<script>

	function popupCenter(pageURL, w,h) {
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		var targetWin = window.open (pageURL, "_blank",'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
	function pinIt()
	{
	   var e = document.createElement('script');
	   e.setAttribute('type','text/javascript');
	   e.setAttribute('charset','UTF-8');
	   e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
	   document.body.appendChild(e);
	}
</script>


<div class="mo-openid-app-icons circle ">
	<p style="color: #<?php echo esc_attr( $heading_color ); ?>;">
								  <?php

									if ( $orientation == 'hor' ) {

										echo esc_attr( $heading_text );
										?>
		</p>
			 <div class="horizontal">
											<?php
											if ( $custom_theme == 'custom' ) {
												if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
													$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
													?>
						<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
													$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
													?>
						<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_google_share_enable' ) ) {
													$link = esc_url( 'https://plus.google.com/share?url=' . $url );
													?>
						<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
													$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
													?>
						<a rel='nofollow' title="Vkontakte"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
													$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
													?>
						<a rel='nofollow' title="Tumblr"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
													$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="StumbleUpon"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
													$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
													?>
						<a rel='nofollow' title="LinkekIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
													$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Reddit"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Pinterest"  href='javascript:pinIt();' class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="padding-top:3px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 10 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
													$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Pocket"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_digg_share_enable' ) ) {
													$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Digg"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_mail_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=<?php echo esc_attr( $email_body ); ?>',800,500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_print_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Print this page" onclick="javascript:window.print()"class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

													?>
						<a rel='nofollow'  title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
							<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

													?>
						<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
							<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
											} elseif ( $custom_theme == 'customFont' ) {
												if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
													$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
													?>
						<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
													$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
													?>
						<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_google_share_enable' ) ) {
													$link = esc_url( 'https://plus.google.com/share?url=' . $url );
													?>
						<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
													$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
													?>
						<a rel='nofollow' title="Vkontakte" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
													$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Tumblr"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
													$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="StumbleUpon"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
													$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
													?>
						<a rel='nofollow' title="LinkedIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
													$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Reddit"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Pinterest"  href='javascript:pinIt();' class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
													$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Pocket"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_digg_share_enable' ) ) {
													$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
						<a rel='nofollow' title="Digg"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons - 6 ); ?>px !important"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_mail_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=<?php echo esc_attr( $email_body ); ?>',800,500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class=" <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_print_share_enable' ) ) {
													?>
						<a rel='nofollow' title="Print this page" onclick="javascript:window.print()" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class=" <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

													?>
						<a rel='nofollow'  title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
							<i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

													?>
						<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
							<i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 4 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
											} else {
												if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
													$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
													?>
					<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['facebook'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
													$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
													?>
					<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['twitter'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_google_share_enable' ) ) {
													$link = esc_url( 'https://plus.google.com/share?url=' . $url );
													?>
					<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color: <?php echo esc_attr( $default_color['google'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important;"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
													$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
													?>
					<a rel='nofollow' title="Vkontakte"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['vkontakte'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
													$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
													?>
					<a rel='nofollow' title="Tumblr"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['tumblr'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
													$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
					<a rel='nofollow' title="StumbleUpon"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['stumble'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
													$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
													?>
					<a rel='nofollow' title="LinkekIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['linkedin'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
													$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
					<a rel='nofollow' title="Reddit"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['reddit'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
													?>
					<a rel='nofollow' title="Pinterest"  href='javascript:pinIt();' class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="padding-top:3px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 10 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['pinterest'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
													$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
													?>
					<a rel='nofollow' title="Pocket"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['pocket'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_digg_share_enable' ) ) {
													$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
													?>
					<a rel='nofollow' title="Digg"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['digg'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}

												if ( get_option( 'mo_openid_mail_share_enable' ) ) {
													?>
					<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=
																											   <?php
																												echo esc_attr( $email_body )
																												?>
					',800,500);" class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['mail'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_print_share_enable' ) ) {
													?>
					<a rel='nofollow' title="Print this page" onclick="javascript:window.print()"class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['print'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
												if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

													?>
					<a rel='nofollow'  title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['whatsapp'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

													?>
				<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link" style="margin-left : <?php echo esc_attr( $spaceBetweenIcons ); ?>px !important">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['whatsapp'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
													<?php
												}
											}
											?>
			</p>
		</div>
										<?php
									}
									?>
	<?php if ( $orientation == 'ver' ) { ?>
		<div id="mo_floating_vertical_widget">
			<?php
			if ( $custom_theme == 'custom' ) {
				if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
					$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
					?>
					<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
					$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
					?>
					<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_google_share_enable' ) ) {
					$link = esc_url( 'https://plus.google.com/share?url=' . $url );
					?>
					<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important; padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
					$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
					?>
					<a rel='nofollow' title="Vkontakte" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important; padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
					$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Tumblr"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
					$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="StumbleUpon"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
					$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
					?>
					<a rel='nofollow' title="LinkedIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
					$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Reddit"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Pinterest"  href='javascript:pinIt();' class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:3px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 10 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
					$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Pocket"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_digg_share_enable' ) ) {
					$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Digg"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_mail_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=<?php echo esc_attr( $email_body ); ?>',800,500);" class="mo-openid-share-link">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_print_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Print this page" onclick="javascript:window.print()"class="mo-openid-share-link">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

					?>
					<a rel='nofollow' title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

					?>
					<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
						<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:#<?php echo esc_attr( $custom_color ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
			} elseif ( $custom_theme == 'customFont' ) {
				if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
					$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
					?>
					<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top : 4px !important;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
					$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
					?>
					<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_google_share_enable' ) ) {
					$link = esc_url( 'https://plus.google.com/share?url=' . $url );
					?>
					<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
					$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
					?>
					<a rel='nofollow' title="Vkontakte" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link"><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
					$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Tumblr" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
					$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="StumbleUpon" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
					$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
					?>
					<a rel='nofollow' title="LinkedIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px !important;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
					$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Reddit" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Pinterest" href='javascript:pinIt();' class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize - 5 ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
					$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Pocket" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_digg_share_enable' ) ) {
					$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
					<a rel='nofollow' title="Digg" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_mail_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=<?php echo esc_attr( $email_body ); ?>',800,500);" class="mo-openid-share-link">
					<i class=" <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style=" padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_print_share_enable' ) ) {
					?>
					<a rel='nofollow' title="Print this page" onclick="javascript:window.print()" class="mo-openid-share-link">
					<i class=" <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

					?>
					<a rel='nofollow'title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
						<i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

					?>
					<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
						<i class=" <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:4px;text-align:center;color:#<?php echo esc_attr( $fontColor ); ?> !important;font-size:<?php echo esc_attr( $sharingSize ); ?>px !important;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
			} else {
				if ( get_option( 'mo_openid_facebook_share_enable' ) ) {
					$link = esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' );
					?>
				<a rel='nofollow' title="Facebook" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 400);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-facebook" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['facebook'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_twitter_share_enable' ) ) {
					$link = esc_url( empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username );
					?>
				<a rel='nofollow' title="Twitter" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 600, 300);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-twitter" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['twitter'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_google_share_enable' ) ) {
					$link = esc_url( 'https://plus.google.com/share?url=' . $url );
					?>
				<a rel='nofollow' title="Google" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-google-plus" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important; padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['google'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_vkontakte_share_enable' ) ) {
					$link = esc_url( 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt );
					?>
				<a rel='nofollow' title="Vkontakte" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" style="margin-bottom:<?php echo esc_attr( $space_icons ); ?>px !important;"><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-vk" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important; padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['vkontakte'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_tumblr_share_enable' ) ) {
					$link = esc_url( 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title );
					?>
				<a rel='nofollow' title="Tumblr"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-tumblr" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['tumblr'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_stumble_share_enable' ) ) {
					$link = esc_url( 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
				<a rel='nofollow' title="StumbleUpon"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-stumbleupon" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['stumble'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_linkedin_share_enable' ) ) {
					$link = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt );
					?>
				<a rel='nofollow' title="LinkedIn" onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-linkedin" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['linkedin'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_reddit_share_enable' ) ) {
					$link = esc_url( 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
				<a rel='nofollow' title="Reddit"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-reddit" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['reddit'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pinterest_share_enable' ) ) {
					?>
				<a rel='nofollow' title="Pinterest"  href='javascript:pinIt();' class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-pinterest" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px !important;padding-top:3px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 10 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['pinterest'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_pocket_share_enable' ) ) {
					$link = esc_url( 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title );
					?>
				<a rel='nofollow' title="Pocket"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-get-pocket" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['pocket'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}

				if ( get_option( 'mo_openid_digg_share_enable' ) ) {
					$link = esc_url( 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title );
					?>
				<a rel='nofollow' title="Digg"  onclick="popupCenter('<?php echo esc_attr( $link ); ?>', 800, 500);" class="mo-openid-share-link" ><i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-digg" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['digg'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_mail_share_enable' ) ) {
					?>
				<a rel='nofollow' title="Email this page" onclick="popupCenter('mailto:?subject=<?php echo esc_attr( $email_subject ); ?>&amp;body=<?php echo esc_attr( $email_body ); ?>',800,500);" class="mo-openid-share-link">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> far fa-envelope" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['mail'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_print_share_enable' ) ) {
					?>
				<a rel='nofollow' title="Print this page" onclick="javascript:window.print()"class="mo-openid-share-link">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fas fa-print" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['print'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
				if ( get_option( 'mo_openid_whatsapp_share_enable' ) && wp_is_mobile() ) {

					?>
				<a rel='nofollow' title="Whatsapp" href='whatsapp://send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
					<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['whatsapp'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				} elseif ( get_option( 'mo_openid_whatsapp_share_enable' ) ) {

					?>
			<a rel='nofollow' title="Whatsapp" target ='_blank' href='https://web.whatsapp.com/send?text=<?php echo esc_url( $url ); ?>'  class="mo-openid-share-link">
				<i class="mo-custom-share-icon <?php echo esc_attr( $selected_theme ); ?> fab fa-whatsapp" style="margin-bottom:<?php echo esc_attr( $space_icons - 4 ); ?>px!important;padding-top:8px;text-align:center;color:#ffffff;font-size:<?php echo esc_attr( ( $sharingSize - 16 ) ); ?>px !important;background-color:<?php echo esc_attr( $default_color['whatsapp'] ); ?>;height:<?php echo esc_attr( $sharingSize ); ?>px !important;width:<?php echo esc_attr( $sharingSize ); ?>px !important"></i></a>
					<?php
				}
			}
			?>
		</p>
	</div>
		<?php if ( get_option( 'mo_share_vertical_hide_mobile' ) ) { ?>
		<script>
			function hideVerticalShare() {
				var isMobile = false; //initiate as false
				// device detection
				if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
					|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
					isMobile = true;
				}

				if(isMobile) {
					if(jQuery("#mo_floating_vertical_widget"))
						jQuery("#mo_floating_vertical_widget").hide();
				}
			}
			hideVerticalShare();
		</script>
			<?php
		}
	}
	?>
</div><br>
