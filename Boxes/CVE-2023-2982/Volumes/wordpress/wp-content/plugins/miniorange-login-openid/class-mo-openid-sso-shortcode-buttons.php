<?php

function count_convert( $count ) {
	if ( $count >= 1000000 ) {
		$count = $count / 1000000;
		$count = $count . 'M';
	} elseif ( $count >= 1000 ) {
		$ncount = $count / 1000;
		$count  = $ncount . 'K';
	}
	return $count;
}

// shortcode for horizontal sharing
function mo_openid_share_shortcode( $atts = '', $title = '', $excerpt = '' ) {
	wp_enqueue_style( 'mo-wp-style-icon', plugins_url( 'includes/css/mo_openid_login_icons.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ), false );
	if ( get_option( 'mo_openid_fonawesome_load' ) == 1 ) {
		wp_enqueue_style( 'mo-openid-sl-wp-font-awesome', plugins_url( 'includes/css/mo-font-awesome.min.css', __FILE__ ), false );
	}
	wp_enqueue_style( 'mo_openid_admin_settings_style', plugins_url( 'includes/css/mo_openid_style.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ) );

	$html               = '';
	$selected_theme     = isset( $atts['shape'] ) ? esc_attr( $atts['shape'] ) : esc_attr( get_option( 'mo_openid_share_theme' ) );
	$selected_direction = esc_attr( get_option( 'mo_openid_share_widget_customize_direction' ) );
	$sharingSize        = isset( $atts['size'] ) ? esc_attr( $atts['size'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_size' ) );
	$custom_color       = isset( $atts['backgroundcolor'] ) ? esc_attr( $atts['backgroundcolor'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_color' ) );
	$custom_theme       = isset( $atts['theme'] ) ? esc_attr( $atts['theme'] ) : esc_attr( get_option( 'mo_openid_share_custom_theme' ) );
	$fontColor          = isset( $atts['fontcolor'] ) ? esc_attr( $atts['fontcolor'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_font' ) );
	$spaceBetweenIcons  = isset( $atts['space'] ) ? esc_attr( $atts['space'] ) : esc_attr( get_option( 'mo_sharing_icon_space' ) );
	$textColor          = isset( $atts['color'] ) ? esc_attr( $atts['color'] ) : '#' . esc_attr( get_option( 'mo_openid_share_widget_customize_text_color' ) );
	$text               = isset( $atts['heading'] ) ? esc_attr( $atts ['heading'] ) : esc_attr( get_option( 'mo_openid_share_widget_customize_text' ) );
	$twitter_username   = get_option( 'mo_openid_share_twitter_username' );
	$url                = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : esc_url( get_site_url() );
	$title              = esc_attr( get_the_title() );
	$sharing_counts     = esc_attr( get_option( 'mo_openid_share_count' ) );
	$email_subject      = esc_html( get_option( 'mo_openid_share_email_subject' ) );
	$email_body         = get_option( 'mo_openid_share_email_body' );
	$email_body         = str_replace( '##url##', $url, $email_body );

	global $share_apps;
	$share_apps = array(
		'facebook'         => array( '#1877F2' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' ),
		'twitter'          => array( '#2795e9' => empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username ),
		'google'           => array( '#DB4437' => 'https://plus.google.com/share?url=' . $url ),
		'vkontakte'        => array( '#466482' => 'https://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt ),
		'linkedin'         => array( '#007bb6' => 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt ),
		'tumblr'           => array( '#34465d' => 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $title ),
		'stumble'          => array( '#eb4924' => 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title ),
		'reddit'           => array( '#ff4301' => 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title ),
		'pinterest'        => array( '#cb2027' => 'javascript:pinIt();' ),
		'pocket'           => array( '#ff5700' => 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title ),
		'digg'             => array( '#000000' => 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title ),
		'delicious'        => array( '#205cc0' => 'http://www.delicious.com/save?v=5&noui&jump=close&url=' . $url . '&amp;title=' . $title ),
		'odnoklassniki'    => array( '#ed812b' => 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=' . $title . '&amp;st._surl=' . $url ),
		'mail'             => array( '#00acee' => 'mailto:?subject=' . $title . '&amp;body=' . $url ),
		'print'            => array( '#e0205a' => 'javascript:window.print()' ),
		'whatsapp'         => array( '#25D366' => 'https://web.whatsapp.com/send?text=' . $url ),
		'amazon'           => array( '#ff9900' => 'http://www.amazon.com/wishlist/add?u=' . $url . '&title=' . $title ),
		'telegram'         => array( '#0088cc' => 'https://telegram.me/share/?url=' . $url . '&amp;title=' . $title ),
		'line'             => array( '#329555' => 'https://social-plugins.line.me/lineit/share?u=%encoded_post_url=' . $url . '%&title=' . $title ),
		'yahoo_mail'       => array( '#430297' => '//compose.mail.yahoo.com/?subject=' . $title . '&amp;body=' . $url ),
		'instapaper'       => array( '#000000' => 'http://www.instapaper.com/edit?u=' . $url . '&title=' . $title ),
		'livejournal'      => array( '#306599' => 'http://www.livejournal.com/update.bml?subject=' . $title . '&amp;body=' . $url ),
		'mix'              => array( '#FF0000' => 'https://mix.com/mixit?url=' . $url ),
		'aol_mail'         => array( '#0060a3' => 'http://webmail.aol.com/25045/aol/en-us/Mail/compose-message.aspx?subject=' . $title . '&amp;body=' . $url ),
		'mewe'             => array( '#409d16' => 'https://mewe.com/share?link=' . $url ),
		'qzone'            => array( '#e8ff00' => 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?u=%encoded_post_url=' . $url ),
		'google_gmail'     => array( '#004f9f' => 'https://mail.google.com/mail/?ui=2&view=cm&fs=1&tf=1&su=' . $title . '&body=' . $url ),
		'typepad_post'     => array( '#04800c' => 'https://www.typepad.com/services/quickpost/post?v=2&qp_show=ac&qp_title=' . $title . '&qp_href=' . $url ),
		'fark'             => array( '#654321' => 'https://www.fark.com/submit?new_url=' . $url ),
		'google_bookmark'  => array( '#3b3b38' => 'https://www.google.com/bookmarks/mark?op=edit&bkmk=' . $url . '&title=' . $title ),
		'fintel'           => array( '#24a0ed' => 'https://fintel.io/submit?url=' . $url ),
		'mendeley'         => array( '#008080' => 'https://www.mendeley.com/sign-in/' ),
		'slashdot'         => array( '#126A70' => '//slashdot.org/submission?url=' . $url ),
		'wanelo'           => array( '#FFF700' => '//wanelo.com/p/post?bookmarklet=&images%5B%5D=&url=' . $url . '&title=' . $title ),
		'google_classroom' => array( '#F5BA14' => 'https://classroom.google.com/u/0/share?url=' . $url ),
		'yummly'           => array( '#F0780E' => 'http://www.yummly.com/urb/verify?url=' . $url . ' &title=' . $title ),
		'hacker_news'      => array( '#FF6600' => 'https://news.ycombinator.com/submitlink?url=' . $url . ' &title=' . $title ),
		'kakao'            => array( '#ffe812' => 'https://story.kakao.com/share?url=' . $url ),
		'plurk'            => array( '#f00' => '//www.plurk.com/m?content=' . $url . '&qualifier=' . $title ),
		'trello'           => array( '#0079bf' => 'https://trello.com/add-card?mode=popup&url=' . $url . '&name=' . $title . '&desc=' ),
		'wykop'            => array( '#266F' => '//www.wykop.pl/dodaj?url=' . $url . '&title=' . $title ),
		'sina_weibo'       => array( '#df2029' => '//service.weibo.com/share/share.php?url=' . $url . '&title=' . $title ),
		'renren'           => array( '#2500B8' => '//www.connect.renren.com/share/sharer?url=' . $url . '&title=' . $title ),
		'buffer'           => array( '#000000' => 'https://buffer.com/add?url=' . $url . '&title=' . $title ),
	);

	if ( $fontColor ) {
		if ( ctype_xdigit( $fontColor ) && strlen( $fontColor ) == 6 && strpos( $fontColor, '#' ) == false ) {
			$fontColor = '#' . $fontColor;
		} else {
			$fontColor;
		}
	}

	$html .= '<div class="mo-openid-app-icons circle ">';

	$html .= '<p style="margin-top:4% !important; margin-bottom:0px !important; color:' . $textColor . '">';
	if ( 1 == 1 ) {
		$html .= $text . '</p>';
		$html .= "<div class='horizontal'>";
		if ( $custom_theme == 'custom' ) {
			if ( $sharing_counts ) {
				$html .= "<div class='mo_openid_sharecount'><ul class='mo_openid_share_count_icon'>";
			}

			foreach ( $share_apps as $share_app => $share_colors ) {
				foreach ( $share_colors as $share_color => $share_link ) {
					$share_icon = 'fab fa-' . $share_app;
					if ( $share_app == 'vkontakte' ) {
						$share_icon = 'fab fa-vk';
					}
					if ( $share_app == 'stumble' ) {
						$share_icon = 'fab fa-stumbleupon';
					}
					if ( $share_app == 'pocket' ) {
						$share_icon = 'fab fa-get-pocket';
					}
					if ( $share_app == 'mail' ) {
						$share_icon = 'far fa-envelope';
					}
					if ( $share_app == 'print' ) {
						$share_icon = 'fas fa-print ';
					}
					if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
						if ( $sharing_counts ) {
							$html .= '<li>';
						} $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(" . '"' . $share_link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ( $spaceBetweenIcons ) . "px !important'><i class='mo-custom-share-icon " . $selected_theme . ' ' . $share_icon . "' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" . ( $sharingSize - 16 ) . 'px !important;background-color:#' . $custom_color . ';height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
						if ( $sharing_counts ) {
							$html .= "<span2 style='margin-left : " . ( $spaceBetweenIcons ) . "px !important'></span2></li>";
						};
					}
				}
			}
			if ( $sharing_counts ) {
				$html .= '</ul></div>';
			}
		} elseif ( $custom_theme == 'customFont' ) {
			if ( $sharing_counts ) {
				$html .= "<div class='mo_openid_sharecount'><ul class='mo_openid_share_count_icon'>";
			}

			foreach ( $share_apps as $share_app => $share_colors ) {
				foreach ( $share_colors as $share_color => $share_link ) {
					$share_icon = 'fab fa-' . $share_app;
					if ( $share_app == 'vkontakte' ) {
						$share_icon = 'fab fa-vk';
					}
					if ( $share_app == 'stumble' ) {
						$share_icon = 'fab fa-stumbleupon';
					}
					if ( $share_app == 'pocket' ) {
						$share_icon = 'fab fa-get-pocket';
					}
					if ( $share_app == 'mail' ) {
						$share_icon = 'far fa-envelope';
					}
					if ( $share_app == 'print' ) {
						$share_icon = 'fas fa-print ';
					}
					if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
						if ( $sharing_counts ) {
							$html .= "<li style='line-height: 0.0px'>";
						} $html .= "<a rel='nofollow' title='" . $share_app . "' onclick='popupCenter(" . '"' . $share_link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ( $spaceBetweenIcons - 6 ) . "px !important'><i class=' " . $selected_theme . ' ' . $share_icon . "' style='padding-top:4px;text-align:center;color:" . $fontColor . ';font-size:' . $sharingSize . 'px !important;height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
						if ( $sharing_counts ) {
							$html .= "<span2 style='margin-left : " . ( $spaceBetweenIcons ) . "px !important'></span2></li>";
						};
					}
				}
			}
			if ( $sharing_counts ) {
				$html .= '</ul></div>';

			}
		} else {

			if ( $sharing_counts ) {
				$html .= "<div class='mo_openid_sharecount'><ul class='mo_openid_share_count_icon'>";
			}

			foreach ( $share_apps as $share_app => $share_colors ) {
				foreach ( $share_colors as $share_color => $share_link ) {
					$share_icon = 'fab fa-' . $share_app;
					if ( $share_app == 'vkontakte' ) {
						$share_icon = 'fab fa-vk';
					}
					if ( $share_app == 'stumble' ) {
						$share_icon = 'fab fa-stumbleupon';
					}
					if ( $share_app == 'pocket' ) {
						$share_icon = 'fab fa-get-pocket';
					}
					if ( $share_app == 'mail' ) {
						$share_icon = 'far fa-envelope';
					}
					if ( $share_app == 'print' ) {
						$share_icon = 'fas fa-print ';
					}
					if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
						if ( $sharing_counts ) {
							$html .= '<li>';
						} $html .= "<a rel='nofollow' title='" . $share_app . "' onclick='popupCenter(" . '"' . $share_link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ( $spaceBetweenIcons ) . "px !important'><i class='mo-custom-share-icon " . $selected_theme . ' ' . $share_icon . "' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" . ( $sharingSize - 16 ) . 'px !important;background-color:' . $share_color . ';height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
						if ( $sharing_counts ) {
							$html .= "<span2 style='margin-left : " . ( $spaceBetweenIcons ) . "px !important'></span2></li>";
						};
					}
				}
			}
			if ( $sharing_counts ) {
				$html .= '</ul></div>';

			}
		}
		$html .= '</div>';
	}

	$html .= '</p></div><br/>';

	$html .= '<script>';

	$html .= 'function popupCenter(pageURL, w,h) {';
	$html .= 'var left = (screen.width/2)-(w/2);';
	$html .= 'var top = (screen.height/2)-(h/2);';
	$html .= "var targetWin = window.open (pageURL, '_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);}";

	$html .= 'function pinIt(){';
	$html .= 'var e = document.createElement("script");';
	$html .= "e.setAttribute('type','text/javascript');";
	$html .= "e.setAttribute('charset','UTF-8');";
	$html .= "e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
		document.body.appendChild(e);}";
	$html .= '</script>';

	return $html;

}

function mo_openid_vertical_share_shortcode( $atts = '', $title = '', $excerpt = '' ) {
	wp_enqueue_style( 'mo-wp-style-icon', plugins_url( 'includes/css/mo_openid_login_icons.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ), false );
	if ( get_option( 'mo_openid_fonawesome_load' ) == 1 ) {
		wp_enqueue_style( 'mo-openid-sl-wp-font-awesome', plugins_url( 'includes/css/mo-font-awesome.min.css', __FILE__ ), false );
	}
	wp_enqueue_style( 'mo_openid_admin_settings_style', plugins_url( 'includes/css/mo_openid_style.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ) );

	$html               = '';
	$selected_theme     = isset( $atts['shape'] ) ? esc_attr( $atts['shape'] ) : esc_attr( get_option( 'mo_openid_share_theme' ) );
	$selected_direction = esc_attr( get_option( 'mo_openid_share_widget_customize_direction' ) );
	$sharingSize        = isset( $atts['size'] ) ? esc_attr( $atts['size'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_size' ) );
	$custom_color       = isset( $atts['backgroundcolor'] ) ? esc_attr( $atts['backgroundcolor'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_color' ) );
	$custom_theme       = isset( $atts['theme'] ) ? esc_attr( $atts['theme'] ) : esc_attr( get_option( 'mo_openid_share_custom_theme' ) );
	$fontColor          = isset( $atts['fontcolor'] ) ? esc_attr( $atts['fontcolor'] ) : esc_attr( get_option( 'mo_sharing_icon_custom_font' ) );
	$spaceBetweenIcons  = isset( $atts['space'] ) ? esc_attr( $atts['space'] ) : '10';
	$alignment          = isset( $atts['alignment'] ) ? esc_attr( $atts['alignment'] ) : 'left';
	$left_offset        = isset( $atts['leftoffset'] ) ? esc_attr( $atts['leftoffset'] ) : '20';
	$right_offset       = isset( $atts['rightoffset'] ) ? esc_attr( $atts['rightoffset'] ) : '10';
	$top_offset         = isset( $atts['topoffset'] ) ? esc_attr( $atts['topoffset'] ) : '100';
	$twitter_username   = esc_attr( get_option( 'mo_openid_share_twitter_username' ) );
	$url                = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : esc_url( get_site_url() );
	$email_subject      = esc_attr( get_option( 'mo_openid_share_email_subject' ) );
	$title              = esc_attr( get_the_title() );
	$email_body         = get_option( 'mo_openid_share_email_body' );
	$email_body         = str_replace( '##url##', $url, $email_body );

	global $share_apps;
	$share_apps = array(
		'facebook'         => array( '#1877F2' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&src=sdkpreparse' ),
		'twitter'          => array( '#2795e9' => empty( $twitter_username ) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username ),
		'google'           => array( '#DB4437' => 'https://plus.google.com/share?url=' . $url ),
		'vkontakte'        => array( '#466482' => 'https://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt ),
		'linkedin'         => array( '#007bb6' => 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt ),
		'tumblr'           => array( '#34465d' => 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $title ),
		'stumble'          => array( '#eb4924' => 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title ),
		'reddit'           => array( '#ff4301' => 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title ),
		'pinterest'        => array( '#cb2027' => 'javascript:pinIt();' ),
		'pocket'           => array( '#ff5700' => 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title ),
		'digg'             => array( '#000000' => 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title ),
		'delicious'        => array( '#205cc0' => 'http://www.delicious.com/save?v=5&noui&jump=close&url=' . $url . '&amp;title=' . $title ),
		'odnoklassniki'    => array( '#ed812b' => 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=' . $title . '&amp;st._surl=' . $url ),
		'mail'             => array( '#00acee' => 'mailto:?subject=' . $title . '&amp;body=' . $url ),
		'print'            => array( '#e0205a' => 'javascript:window.print()' ),
		'whatsapp'         => array( '#25D366' => 'https://web.whatsapp.com/send?text=' . $url ),
		'amazon'           => array( '#ff9900' => 'http://www.amazon.com/wishlist/add?u=' . $url . '&title=' . $title ),
		'telegram'         => array( '#0088cc' => 'https://telegram.me/share/?url=' . $url . '&amp;title=' . $title ),
		'line'             => array( '#329555' => 'https://social-plugins.line.me/lineit/share?u=%encoded_post_url=' . $url . '%&title=' . $title ),
		'yahoo_mail'       => array( '#430297' => '//compose.mail.yahoo.com/?subject=' . $title . '&amp;body=' . $url ),
		'instapaper'       => array( '#000000' => 'http://www.instapaper.com/edit?u=' . $url . '&title=' . $title ),
		'livejournal'      => array( '#306599' => 'http://www.livejournal.com/update.bml?subject=' . $title . '&amp;body=' . $url ),
		'mix'              => array( '#FF0000' => 'https://mix.com/mixit?url=' . $url ),
		'aol_mail'         => array( '#0060a3' => 'http://webmail.aol.com/25045/aol/en-us/Mail/compose-message.aspx?subject=' . $title . '&amp;body=' . $url ),
		'mewe'             => array( '#409d16' => 'https://mewe.com/share?link=' . $url ),
		'qzone'            => array( '#e8ff00' => 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?u=%encoded_post_url=' . $url ),
		'google_gmail'     => array( '#004f9f' => 'https://mail.google.com/mail/?ui=2&view=cm&fs=1&tf=1&su=' . $title . '&body=' . $url ),
		'typepad_post'     => array( '#04800c' => 'https://www.typepad.com/services/quickpost/post?v=2&qp_show=ac&qp_title=' . $title . '&qp_href=' . $url ),
		'fark'             => array( '#654321' => 'https://www.fark.com/submit?new_url=' . $url ),
		'google_bookmark'  => array( '#3b3b38' => 'https://www.google.com/bookmarks/mark?op=edit&bkmk=' . $url . '&title=' . $title ),
		'fintel'           => array( '#24a0ed' => 'https://fintel.io/submit?url=' . $url ),
		'mendeley'         => array( '#008080' => 'https://www.mendeley.com/sign-in/' ),
		'slashdot'         => array( '#126A70' => '//slashdot.org/submission?url=' . $url ),
		'wanelo'           => array( '#FFF700' => '//wanelo.com/p/post?bookmarklet=&images%5B%5D=&url=' . $url . '&title=' . $title ),
		'google_classroom' => array( '#F5BA14' => 'https://classroom.google.com/u/0/share?url=' . $url ),
		'yummly'           => array( '#F0780E' => 'http://www.yummly.com/urb/verify?url=' . $url . ' &title=' . $title ),
		'hacker_news'      => array( '#FF6600' => 'https://news.ycombinator.com/submitlink?url=' . $url . ' &title=' . $title ),
		'kakao'            => array( '#ffe812' => 'https://story.kakao.com/share?url=' . $url ),
		'plurk'            => array( '#f00' => '//www.plurk.com/m?content=' . $url . '&qualifier=' . $title ),
		'trello'           => array( '#0079bf' => 'https://trello.com/add-card?mode=popup&url=' . $url . '&name=' . $title . '&desc=' ),
		'wykop'            => array( '#266F' => '//www.wykop.pl/dodaj?url=' . $url . '&title=' . $title ),
		'sina_weibo'       => array( '#df2029' => '//service.weibo.com/share/share.php?url=' . $url . '&title=' . $title ),
		'renren'           => array( '#2500B8' => '//www.connect.renren.com/share/sharer?url=' . $url . '&title=' . $title ),
		'buffer'           => array( '#000000' => 'https://buffer.com/add?url=' . $url . '&title=' . $title ),
	);

	$html .= "<div class='mo_openid_vertical' style='" . ( isset( $alignment ) && $alignment != '' ? $alignment . ': ' . ( ${$alignment . '_offset'} == '' ? 0 : ${$alignment . '_offset'} ) . 'px;' : '' ) . ( isset( $top_offset ) ? 'top: ' . ( $top_offset == '' ? 0 : $top_offset ) . 'px;' : '' ) . "'>";

	$html .= '<div class="mo-openid-app-icons circle ">';
	$html .= '<p>';

	$html .= "<div id='mo_floating_vertical_shortcode'>";
	if ( $custom_theme == 'custom' ) {

		foreach ( $share_apps as $share_app => $share_colors ) {
			foreach ( $share_colors as $share_color => $share_link ) {
				$share_icon = 'fab fa-' . $share_app;
				if ( $share_app == 'vkontakte' ) {
					$share_icon = 'fab fa-vk';
				}
				if ( $share_app == 'stumble' ) {
					$share_icon = 'fab fa-stumbleupon';
				}
				if ( $share_app == 'pocket' ) {
					$share_icon = 'fab fa-get-pocket';
				}
				if ( $share_app == 'mail' ) {
					$share_icon = 'far fa-envelope';
				}
				if ( $share_app == 'print' ) {
					$share_icon = 'fas fa-print ';
				}
				if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
					$html .= "<a rel='nofollow' title='" . $share_app . "' onclick='popupCenter(" . '"' . $share_link . '"' . ", 1000, 500);' class='mo-openid-share-link' style='margin-bottom : " . $spaceBetweenIcons . "px !important'><i class='mo-custom-share-icon " . $selected_theme . ' ' . $share_icon . "' style='margin-bottom : " . ( $spaceBetweenIcons - 4 ) . 'px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:' . ( $sharingSize - 16 ) . 'px !important;background-color:#' . $custom_color . ';height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
				}
			}
		}
	} elseif ( $custom_theme == 'customFont' ) {

		foreach ( $share_apps as $share_app => $share_colors ) {
			foreach ( $share_colors as $share_color => $share_link ) {
				$share_icon = 'fab fa-' . $share_app;
				if ( $share_app == 'vkontakte' ) {
					$share_icon = 'fab fa-vk';
				}
				if ( $share_app == 'stumble' ) {
					$share_icon = 'fab fa-stumbleupon';
				}
				if ( $share_app == 'pocket' ) {
					$share_icon = 'fab fa-get-pocket';
				}
				if ( $share_app == 'mail' ) {
					$share_icon = 'far fa-envelope';
				}
				if ( $share_app == 'print' ) {
					$share_icon = 'fas fa-print ';
				}
				if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
					$html .= "<a rel='nofollow' title='" . $share_app . "' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='" . $share_icon . "' style='margin-bottom : " . ( $spaceBetweenIcons - 4 ) . 'px !important;padding-top:4px;text-align:center;color:' . $fontColor . ';font-size:' . $sharingSize . 'px !important;height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
				}
			}
		}
	} else {

		foreach ( $share_apps as $share_app => $share_colors ) {
			foreach ( $share_colors as $share_color => $share_link ) {
				$share_icon = 'fab fa-' . $share_app;
				if ( $share_app == 'vkontakte' ) {
					$share_icon = 'fab fa-vk';
				}
				if ( $share_app == 'stumble' ) {
					$share_icon = 'fab fa-stumbleupon';
				}
				if ( $share_app == 'pocket' ) {
					$share_icon = 'fab fa-get-pocket';
				}
				if ( $share_app == 'mail' ) {
					$share_icon = 'far fa-envelope';
				}
				if ( $share_app == 'print' ) {
					$share_icon = 'fas fa-print ';
				}
				if ( get_option( 'mo_openid_' . $share_app . '_share_enable' ) ) {
					$html .= "<a rel='nofollow' title='Facebook' onclick='popupCenter(" . '"' . $share_link . '"' . ", 1000, 500);' class='mo-openid-share-link' style='margin-bottom : " . $spaceBetweenIcons . "px !important'><i class='mo-custom-share-icon " . $selected_theme . ' ' . $share_icon . "' style='margin-bottom : " . ( $spaceBetweenIcons - 4 ) . 'px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:' . ( $sharingSize - 16 ) . 'px !important;background-color:' . $share_color . ';height:' . $sharingSize . 'px !important;width:' . $sharingSize . "px !important;'></i></a>";
				}
			}
		}
	}
	$html .= '</div>';

	$html .= '</p></div></div>';

	if ( get_option( 'mo_share_vertical_hide_mobile' ) ) {
		$html .= '<script>';
		$html .= 'function hideVerticalShare() {';
		$html .= 'var isMobile = false;';
		$html .= 'if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {';
		$html .= 'isMobile = true;';
		$html .= '}';

		$html .= 'if(isMobile) {';
		$html .= "if(jQuery('#mo_floating_vertical_shortcode'))";
		$html .= 'jQuery("#mo_floating_vertical_shortcode").hide();';
		$html .= '}';
		$html .= '}';
		$html .= 'hideVerticalShare();';
		$html .= '</script>';
	}

	$html .= '<script>';

	$html .= 'function popupCenter(pageURL, w,h) {';
	$html .= 'var left = (screen.width/2)-(w/2);';
	$html .= 'var top = (screen.height/2)-(h/2);';
	$html .= "var targetWin = window.open (pageURL, '_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);}";

	$html .= 'function pinIt(){';
	$html .= 'var e = document.createElement("script");';
	$html .= "e.setAttribute('type','text/javascript');";
	$html .= "e.setAttribute('charset','UTF-8');";
	$html .= "e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);";
	$html .= 'document.body.appendChild(e);}';
	$html .= '</script>';

	return $html;

}
