<?php
function mo_openid_social_comment( $post, $url ) {

	?>
		<script>
		function moOpenIDShowCommentForms(){
			var commentFormElement = document.getElementById("respond");
			if(commentFormElement) {
			<?php wp_enqueue_script( 'moopenid-comment-fb', plugins_url( 'includes/js/social/fb_comment.js', __FILE__ ), array( 'jquery' ) ); ?>
				var commentForm = '<div><h3 id="mo_reply_label" class="comment-reply-title"><?php echo esc_attr( get_option( 'mo_openid_social_comment_heading_label' ) ); ?></h3><br/><ul class="mo_openid_comment_tab">';
			<?php
			if ( get_option( 'mo_openid_social_comment_default' ) ) {
				$commentsCount = wp_count_comments( $post->ID );
				?>
				commentForm += '<li id="moopenid_social_comment_default" class="mo_openid_selected_tab"><?php echo esc_html( get_option( 'mo_openid_social_comment_default_label' ) ); ?>(<?php echo esc_attr( ( $commentsCount && isset( $commentsCount->approved ) ? $commentsCount->approved : '' ) ); ?>)</li>';
				<?php } if ( get_option( 'mo_openid_social_comment_fb' ) ) { ?>
				commentForm += '<li id="moopenid_social_comment_fb"><?php echo esc_html( get_option( 'mo_openid_social_comment_fb_label' ) ); ?></li>';
				<?php } if ( get_option( 'mo_openid_social_comment_disqus' ) ) { ?>
				commentForm += '<li id="moopenid_social_comment_disqus"><?php echo esc_html( get_option( 'mo_openid_social_comment_disqus_label' ) ); ?></li>';
				<?php } ?>
				commentForm += '</ul>';
				commentForm += '<br/><div id="moopenid_comment_form_default" style="display:none;">';
				commentForm += document.getElementById("respond").innerHTML;
				commentForm += '</div>';
				commentForm += '<div id="moopenid_comment_form_fb" style="display:none;"><div class="fb-comments" data-href=' + '"<?php echo esc_url( $url ); ?>"' + '></div></div>';
				commentForm += '<br/><div id="moopenid_comment_form_disqus" style="display:none;"><div id="disqus_thread"></div></div>';
				commentForm += '</div>';
				document.getElementById("respond").innerHTML = commentForm;
				document.getElementById("reply-title")&&jQuery("#reply-title").remove();

				(function() {  // REQUIRED CONFIGURATION VARIABLE: EDIT THE SHORTNAME BELOW
					var d = document, s = d.createElement('script');

					// IMPORTANT: Replace EXAMPLE with your forum shortname!
					s.src = 'https://<?php echo esc_attr( get_option( 'mo_disqus_shortname' ) ); ?>.disqus.com/embed.js';

					s.setAttribute('data-timestamp', +new Date());
					(d.head || d.body).appendChild(s);
				})();

			}
		}

		</script>
		<?php
}

function mo_openid_comments_shortcode() {
	$html  = '';
	$html .= '<div id="mo_comment_shortcode"></div>';
	$html .= '<script>';
	$html .= 'window.onload=function(){';
	$html .= 'var comments = document.getElementById("comments");';
	$html .= 'document.getElementById("mo_comment_shortcode").innerHTML = comments.innerHTML;';
	$html .= 'comments.parentNode.removeChild(comments);';

	$html .= 'if(document.getElementById("moopenid_social_comment_default")) {';
	$html .= 'jQuery("#moopenid_comment_form_default").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_social_comment_default").addClass("mo_openid_selected_tab");';
	$html .= '} else if(document.getElementById("moopenid_social_comment_fb")) {';
	$html .= 'jQuery("#moopenid_comment_form_fb").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_social_comment_fb").addClass("mo_openid_selected_tab");';
	$html .= '} else if(document.getElementById("moopenid_social_comment_disqus")){';
	$html .= 'Query("#moopenid_comment_form_disqus").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_social_comment_disqus").addClass("mo_openid_selected_tab");';
	$html .= '}';

	$html .= 'jQuery("#moopenid_social_comment_fb").click(function(){';
	$html .= 'jQuery("#moopenid_comment_form_fb").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_comment_form_disqus").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_comment_form_default").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_social_comment_fb").addClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_default").removeClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_disqus").removeClass("mo_openid_selected_tab");';
	$html .= '});';
	$html .= 'jQuery("#moopenid_social_comment_default").click(function(){';
	$html .= 'jQuery("#moopenid_comment_form_fb").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_comment_form_disqus").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_comment_form_default").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_social_comment_fb").removeClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_default").addClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_disqus").removeClass("mo_openid_selected_tab");';
	$html .= '});';
	$html .= 'jQuery("#moopenid_social_comment_disqus").click(function(){';
	$html .= 'jQuery("#moopenid_comment_form_fb").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_comment_form_disqus").attr("style","display:block");';
	$html .= 'jQuery("#moopenid_comment_form_default").attr("style","display:none");';
	$html .= 'jQuery("#moopenid_social_comment_fb").removeClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_default").removeClass("mo_openid_selected_tab");';
	$html .= 'jQuery("#moopenid_social_comment_disqus").addClass("mo_openid_selected_tab");';
	$html .= '});';

	$html .= '}';
	$html .= '</script>';

	return $html;
}
