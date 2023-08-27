<?php
/**
 * Echo comments fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_comments_list_before_markup', 'jupiterx_comments_title' );
/**
 * Echo the comments title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comments_title() {

	jupiterx_open_markup_e( 'jupiterx_comments_title', 'h2', [ 'class' => 'jupiterx-comments-title' ] );

		jupiterx_output_e(
			'jupiterx_comments_title_text', sprintf(
				// translators: Number of comments, one or many.
				_n( '%s Comment', '%s Comments', get_comments_number(), 'jupiterx' ),
				number_format_i18n( get_comments_number() )
			)
		);

	jupiterx_close_markup_e( 'jupiterx_comments_title', 'h2' );
}

jupiterx_add_smart_action( 'jupiterx_comment_header', 'jupiterx_comment_avatar', 5 );
/**
 * Echo the comment avatar.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_avatar() {
	global $comment;

	$comment_elements = get_theme_mod( 'jupiterx_comment_elements', [
		'avatar',
	] );

	if ( ! in_array( 'avatar', $comment_elements, true ) ) {
		return;
	}

	// Stop here if no avatar.
	$avatar = get_avatar( $comment, $comment->args['avatar_size'] );

	if ( ! $avatar ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_comment_avatar', 'div', array( 'class' => 'jupiterx-comment-avatar' ) );

		echo $avatar; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes get_avatar().

	jupiterx_close_markup_e( 'jupiterx_comment_avatar', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_comment_header', 'jupiterx_comment_author' );
/**
 * Echo the comment author title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_author() {
	jupiterx_open_markup_e(
		'jupiterx_comment_title',
		'div',
		array(
			'class'     => 'jupiterx-comment-title',
			'itemprop'  => 'author',
			'itemscope' => 'itemscope',
			'itemtype'  => 'http://schema.org/Person',
		)
	);

		jupiterx_selfclose_markup_e(
			'jupiterx_post_meta_author_name_meta',
			'meta',
			array(
				'itemprop' => 'name',
				'content'  => get_comment_author(),
			)
		);

		jupiterx_open_markup_e(
			'jupiterx_comment_username',
			'span',
			[
				'class' => 'jupiterx-comment-username',
			]
		);

			echo get_comment_author_link();

		jupiterx_close_markup_e( 'jupiterx_comment_username', 'span' );

	jupiterx_close_markup_e( 'jupiterx_comment_title', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_comment_title_append_markup', 'jupiterx_comment_badges' );
/**
 * Echo the comment badges.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_badges() {
	global $comment;
	$comment_elements = get_theme_mod( 'jupiterx_comment_elements', [
		'role',
	] );

	if ( ! in_array( 'role', $comment_elements, true ) ) {
		return;
	}

	// Trackback badge.
	if ( 'trackback' === $comment->comment_type ) {
		jupiterx_open_markup_e( 'jupiterx_trackback_badge', 'span', array( 'class' => 'jupiterx-comment-badge badge badge-pill btn-primary' ) );

			jupiterx_output_e( 'jupiterx_trackback_text', esc_html__( 'Trackback', 'jupiterx' ) );

		jupiterx_close_markup_e( 'jupiterx_trackback_badge', 'span' );
	}

	// Pindback badge.
	if ( 'pingback' === $comment->comment_type ) {
		jupiterx_open_markup_e( 'jupiterx_pingback_badge', 'span', array( 'class' => 'jupiterx-comment-badge badge badge-pill btn-primary' ) );

			jupiterx_output_e( 'jupiterx_pingback_text', esc_html__( 'Pingback', 'jupiterx' ) );

		jupiterx_close_markup_e( 'jupiterx_pingback_badge', 'span' );
	}

	// Moderation badge.
	if ( '0' === $comment->comment_approved ) {
		jupiterx_open_markup_e( 'jupiterx_moderation_badge', 'span', array( 'class' => 'jupiterx-comment-badge badge badge-pill btn-warning' ) );

			jupiterx_output_e( 'jupiterx_moderation_text', esc_html__( 'Awaiting Moderation', 'jupiterx' ) );

		jupiterx_close_markup_e( 'jupiterx_moderation_badge', 'span' );
	}

	// Moderator badge.
	if ( user_can( $comment->user_id, 'moderate_comments' ) ) {
		jupiterx_open_markup_e( 'jupiterx_moderator_badge', 'span', array( 'class' => 'jupiterx-comment-badge badge badge-pill btn-primary' ) );

			jupiterx_output_e( 'jupiterx_moderator_text', esc_html__( 'Moderator', 'jupiterx' ) );

		jupiterx_close_markup_e( 'jupiterx_moderator_badge', 'span' );
	}
}

jupiterx_add_smart_action( 'jupiterx_comment_header', 'jupiterx_comment_metadata', 15 );
/**
 * Echo the comment metadata.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_metadata() {
	$comment_elements = get_theme_mod( 'jupiterx_comment_elements', [
		'date',
	] );

	if ( ! in_array( 'date', $comment_elements, true ) ) {
		return;
	}
	jupiterx_open_markup_e( 'jupiterx_comment_meta', 'div', array( 'class' => 'jupiterx-comment-meta' ) );

		jupiterx_open_markup_e(
			'jupiterx_comment_time',
			'time',
			array(
				'datetime' => get_comment_time( 'c' ),
				'itemprop' => 'datePublished',
			)
		);

			jupiterx_output_e(
				'jupiterx_comment_time_text', sprintf(
					// translators: Date of the comment, time of the comment.
					_x( '%1$s at %2$s', '1: date, 2: time', 'jupiterx' ),
					get_comment_date(),
					get_comment_time()
				)
			);

		jupiterx_close_markup_e( 'jupiterx_comment_time', 'time' );

	jupiterx_close_markup_e( 'jupiterx_comment_meta', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_comment_content', 'jupiterx_comment_content' );
/**
 * Echo the comment content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_content() {
	jupiterx_open_markup_e( 'jupiterx_comment_content_wrapper', 'div', array( 'class' => 'jupiterx-comment-body-wrapper' ) );

		jupiterx_output_e( 'jupiterx_comment_content', jupiterx_render_function( 'comment_text' ) );

	jupiterx_close_markup_e( 'jupiterx_comment_content_wrapper', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_comment_content', 'jupiterx_comment_links', 15 );
/**
 * Echo the comment links.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_links() {
	global $comment;

	jupiterx_open_markup_e( 'jupiterx_comment_links', 'ul', array( 'class' => 'jupiterx-comment-links list-inline' ) );

		$custom_style = apply_filters( 'jupiterx_post_comments_has_custom_style', false );

		// Reply.
		if ( $custom_style ) {
			echo '<i class="fa fa-comment-dots"></i>';
		}

		echo get_comment_reply_link( // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
			array_merge(
				$comment->args, array(
					'add_below' => 'comment-content',
					'depth'     => $comment->depth,
					'max_depth' => $comment->args['max_depth'],
					'before'    => jupiterx_open_markup( 'jupiterx_comment_item[_reply]', 'li', [ 'class' => 'list-inline-item' ] ),
					'after'     => jupiterx_close_markup( 'jupiterx_comment_item[_reply]', 'li' ),
				)
			)
		);

		// Edit.
	if ( current_user_can( 'moderate_comments' ) ) :
		jupiterx_open_markup_e( 'jupiterx_comment_item[_edit]', 'li', [ 'class' => 'list-inline-item' ] );

			jupiterx_open_markup_e(
				'jupiterx_comment_item_link[_edit]',
				'a',
				array(
					'href' => esc_url( get_edit_comment_link( $comment->comment_ID ) ), // Automatically escaped.
				)
			);

				jupiterx_output_e( 'jupiterx_comment_edit_text', __( 'Edit', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_comment_item_link[_edit]', 'a' );

		jupiterx_close_markup_e( 'jupiterx_comment_item[_edit]', 'li' );
endif;

		// Link.
		if ( $custom_style ) {
			echo '<i class="fa fa-paper-plane"></i>';
		}

		jupiterx_open_markup_e( 'jupiterx_comment_item[_link]', 'li', [ 'class' => 'list-inline-item' ] );

			jupiterx_open_markup_e(
				'jupiterx_comment_item_link[_link]',
				'a',
				array(
					'href' => esc_url( get_comment_link( $comment->comment_ID ) ), // Automatically escaped.
				)
			);

				jupiterx_output_e( 'jupiterx_comment_link_text', __( 'Link', 'jupiterx' ) );

			jupiterx_close_markup_e( 'jupiterx_comment_item_link[_link]', 'a' );

		jupiterx_close_markup_e( 'jupiterx_comment_item[_link]', 'li' );

	jupiterx_close_markup_e( 'jupiterx_comment_links', 'ul' );
}

jupiterx_add_smart_action( 'jupiterx_no_comment', 'jupiterx_no_comment' );
/**
 * Echo no comment content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_no_comment() {
	jupiterx_open_markup_e( 'jupiterx_no_comment', 'p', 'class=jupiterx-no-comment' );

		jupiterx_output_e( 'jupiterx_no_comment_text', esc_html__( 'No comment yet, add your voice below!', 'jupiterx' ) );

	jupiterx_close_markup_e( 'jupiterx_no_comment', 'p' );
}

jupiterx_add_smart_action( 'jupiterx_comments_closed', 'jupiterx_comments_closed' );
/**
 * Echo closed comments content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comments_closed() {
	jupiterx_open_markup_e( 'jupiterx_comments_closed', 'p', array( 'class' => 'alert alert-warning' ) );

		jupiterx_output_e( 'jupiterx_comments_closed_text', esc_html__( 'Comments are closed for this article!', 'jupiterx' ) );

	jupiterx_close_markup_e( 'jupiterx_comments_closed', 'p' );
}

jupiterx_add_smart_action( 'jupiterx_comments_list_after_markup', 'jupiterx_comments_navigation' );
/**
 * Echo comments navigation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comments_navigation() {

	if ( get_comment_pages_count() <= 1 && ! get_option( 'page_comments' ) ) {
		return;
	}

	jupiterx_open_markup_e(
		'jupiterx_comments_navigation',
		'ul',
		array(
			'class' => 'jupiterx-pagination pagination',
			'role'  => 'navigation',
		)
	);

		// Previous.
	if ( get_previous_comments_link() ) {
		jupiterx_open_markup_e( 'jupiterx_comments_navigation_item[_previous]', 'li', 'class=jupiterx-pagination-previous page-item' );

			echo get_previous_comments_link( // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
				jupiterx_output( 'jupiterx_previous_text[_comments_navigation]', __( 'Previous', 'jupiterx' ) )
			);

		jupiterx_close_markup_e( 'jupiterx_comments_navigation_item[_previous]', 'li' );
	}

		// Next.
	if ( get_next_comments_link() ) {
		jupiterx_open_markup_e( 'jupiterx_comments_navigation_item[_next]', 'li', 'class=jupiterx-pagination-next page-item ml-auto' );

			echo get_next_comments_link( // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
				jupiterx_output( 'jupiterx_next_text[_comments_navigation]', __( 'Next', 'jupiterx' ) )
			);

		jupiterx_close_markup_e( 'jupiterx_comments_navigation_item_[_next]', 'li' );
	}

	jupiterx_close_markup_e( 'jupiterx_comments_navigation', 'ul' );
}

jupiterx_add_filter( 'previous_comments_link_attributes', 'jupiterx_previous_comments_link_attributes' );
/**
 * Filter previous comments link attributes.
 *
 * @since 1.0.0
 *
 * @param string $attributes Attributes for the anchor tag.
 *
 * @return string
 */
function jupiterx_previous_comments_link_attributes( $attributes ) {
	$attributes = 'class="btn btn-outline-secondary" rel="next"';

	return $attributes;
};

jupiterx_add_filter( 'next_comments_link_attributes', 'jupiterx_next_comments_link_attributes' );
/**
 * Filter next comments link attributes.
 *
 * @since 1.0.0
 *
 * @param string $attributes Attributes for the anchor tag.
 *
 * @return string
 */
function jupiterx_next_comments_link_attributes( $attributes ) {
	$attributes = 'class="btn btn-outline-secondary" rel="next"';

	return $attributes;
};

jupiterx_add_smart_action( 'jupiterx_after_open_comments', 'jupiterx_comment_form_divider' );
/**
 * Echo comment divider.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_form_divider() {
	jupiterx_selfclose_markup_e( 'jupiterx_comment_form_divider', 'hr', array( 'class' => 'jupiterx-article-divider' ) );
}

jupiterx_add_smart_action( 'jupiterx_after_open_comments', 'jupiterx_comment_form' );
/**
 * Echo comment navigation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_form() {
	$submit_button_class = 'btn btn-dark';

	$output = jupiterx_open_markup( 'jupiterx_comment_form_wrap', 'div', array( 'class' => 'jupiterx-form jupiterx-comment-form-wrap' ) );

		$output .= jupiterx_render_function( 'comment_form', array( 'title_reply' => jupiterx_output( 'jupiterx_comment_form_title_text', esc_html__( 'Add a Comment', 'jupiterx' ) ) ) );

	$output .= jupiterx_close_markup( 'jupiterx_comment_form_wrap', 'div' );

	if ( get_theme_mod( 'jupiterx_comment_button_full_width' ) ) {
		$submit_button_class .= ' btn-block ';
	}

	$submit = jupiterx_open_markup(
		'jupiterx_comment_form_submit',
		'button',
		array(
			'class' => $submit_button_class,
			'type'  => 'submit',
		)
	);

		$submit .= jupiterx_output( 'jupiterx_comment_form_submit_text', esc_html__( 'Submit', 'jupiterx' ) );

	$submit .= jupiterx_close_markup( 'jupiterx_comment_form_submit', 'button' );

	// WordPress, please make it easier for us.
	echo preg_replace( '#<input[^>]+type="submit"[^>]+>#', $submit, $output ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.
}

// Filter.
jupiterx_add_smart_action( 'cancel_comment_reply_link', 'jupiterx_comment_cancel_reply_link', 10, 3 );
/**
 * Echo comment cancel reply link.
 *
 * This function replaces the default WordPress comment cancel reply link.
 *
 * @since 1.0.0
 *
 * @param string $html HTML.
 * @param string $link Cancel reply link.
 * @param string $text Text to output.
 *
 * @return string
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_comment_cancel_reply_link( $html, $link, $text ) {

	$output = jupiterx_open_markup(
		'jupiterx_comment_cancel_reply_link',
		'a',
		array(
			'rel'   => 'nofollow',
			'id'    => 'cancel-comment-reply-link',
			'class' => 'jupiterx-button jupiterx-button-small jupiterx-button-danger jupiterx-margin-small-right',
			'style' => isset( $_GET['replytocom'] ) ? '' : 'display:none;', // @codingStandardsIgnoreLine
			'href'  => esc_url( $link ), // Automatically escaped.
		)
	);

		$output .= jupiterx_output( 'jupiterx_comment_cancel_reply_link_text', $text );

	$output .= jupiterx_close_markup( 'jupiterx_comment_cancel_reply_link', 'a' );

	return $output;
}

// Filter.
jupiterx_add_smart_action( 'comment_form_field_comment', 'jupiterx_comment_form_comment', 1 );
/**
 * Echo comment textarea field.
 *
 * This function replaces the default WordPress comment textarea field.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_comment_form_comment() {
	$output = jupiterx_open_markup( 'jupiterx_comment_form[_comment]', 'p', 'class=jupiterx-comment-field-wrapper' );

		$output .= jupiterx_open_markup( 'jupiterx_comment_form_label[_comment]', 'label', 'class=sr-only' );

			$output .= jupiterx_output( 'jupiterx_comment_form_label_text[_comment]', __( 'Comment *', 'jupiterx' ) );

		$output .= jupiterx_close_markup( 'jupiterx_comment_form_label[_comment]', 'label' );

		$output .= jupiterx_open_markup(
			'jupiterx_comment_form_field[_comment]', 'textarea', [
				'id'          => 'comment',
				'class'       => 'form-control',
				'name'        => 'comment',
				'required'    => '',
				'rows'        => 8,
				'placeholder' => apply_filters( 'jupiterx_comment_textarea_placeholder', esc_html__( 'Comment *', 'jupiterx' ) ),
			]
		);

		$output .= jupiterx_close_markup( 'jupiterx_comment_form_field[_comment]', 'textarea' );

	$output .= jupiterx_close_markup( 'jupiterx_comment_form[_comment]', 'p' );

	return $output;
}

jupiterx_add_smart_action( 'comment_form_before_fields', 'jupiterx_comment_before_fields' );
/**
 * Echo comment fields opening wraps.
 *
 * This function must be attached to the WordPress 'comment_form_before_fields' action which is only called if
 * the user is not logged in.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_before_fields() {
	jupiterx_open_markup_e( 'jupiterx_comment_fields_wrap', 'div' );

		jupiterx_open_markup_e(
			'jupiterx_comment_fields_inner_wrap', 'div', array(
				'class' => 'row',
			)
		);
}

// Filter.
jupiterx_add_smart_action( 'comment_form_default_fields', 'jupiterx_comment_form_fields' );
/**
 * Modify comment form fields.
 *
 * This function replaces the default WordPress comment fields.
 *
 * @since 1.0.0
 *
 * @param array $fields The WordPress default fields.
 *
 * @return array The modified fields.
 */
function jupiterx_comment_form_fields( $fields ) {

	$commenter = wp_get_current_commenter();

	// Author.
	if ( isset( $fields['author'] ) ) {

		$author = jupiterx_open_markup( 'jupiterx_comment_form[_name]', 'div', [ 'class' => 'form-group col-lg' ] );

			$author .= jupiterx_open_markup( 'jupiterx_comment_form_label[_name]', 'label', 'class=sr-only' );

				$author .= jupiterx_output( 'jupiterx_comment_form_label_text[_name]', __( 'Name *', 'jupiterx' ) );

			$author .= jupiterx_close_markup( 'jupiterx_comment_form_label[_name]', 'label' );

			$author .= jupiterx_selfclose_markup(
				'jupiterx_comment_form_field[_name]', 'input', [
					'id'          => 'author',
					'class'       => 'form-control',
					'type'        => 'text',
					'value'       => esc_attr( $commenter['comment_author'] ),
					'name'        => 'author',
					'required'    => 'required',
					'placeholder' => __( 'Name *', 'jupiterx' ),
				]
			);

		$author .= jupiterx_close_markup( 'jupiterx_comment_form[_name]', 'div' );

		$fields['author'] = $author;
	}

	// Email.
	if ( isset( $fields['email'] ) ) {
		$email = jupiterx_open_markup( 'jupiterx_comment_form[_email]', 'div', [ 'class' => 'form-group col-lg' ] );

			$email .= jupiterx_open_markup( 'jupiterx_comment_form_label[_email]', 'label', 'class=sr-only' );

				$email .= jupiterx_output( 'jupiterx_comment_form_label_text[_email]',
					// translators: Whether or not submitting an email address is required.
					sprintf( __( 'Email %s', 'jupiterx' ), ( get_option( 'require_name_email' ) ? ' *' : '' ) )
				);

			$email .= jupiterx_close_markup( 'jupiterx_comment_form_label[_email]', 'label' );

			$email .= jupiterx_selfclose_markup(
				'jupiterx_comment_form_field[_email]', 'input', [
					'id'          => 'email',
					'class'       => 'form-control',
					'type'        => 'text',
					'value'       => esc_attr( $commenter['comment_author_email'] ),
					'name'        => 'email',
					'required'    => get_option( 'require_name_email' ) ? 'required' : null,
					/* translators: Email field placeholder */
					'placeholder' => sprintf( __( 'Email %s', 'jupiterx' ), ( get_option( 'require_name_email' ) ? ' *' : '' ) ),
				]
			);

		$email .= jupiterx_close_markup( 'jupiterx_comment_form[_email]', 'div' );

		$fields['email'] = $email;
	}

	// Url.
	if ( isset( $fields['url'] ) ) {
		$url = jupiterx_open_markup( 'jupiterx_comment_form[_website]', 'div', [ 'class' => 'form-group col-lg' ] );

			$url .= jupiterx_open_markup( 'jupiterx_comment_form_label[_url]', 'label', 'class=sr-only' );

				$url .= jupiterx_output( 'jupiterx_comment_form_label_text[_url]', __( 'Website', 'jupiterx' ) );

			$url .= jupiterx_close_markup( 'jupiterx_comment_form_label[_url]', 'label' );

			$url .= jupiterx_selfclose_markup(
				'jupiterx_comment_form_field[_url]', 'input', array(
					'id'          => 'url',
					'class'       => 'form-control',
					'type'        => 'text',
					'value'       => esc_attr( $commenter['comment_author_url'] ),
					'name'        => 'url',
					'placeholder' => __( 'Website', 'jupiterx' ),
				)
			);

		$url .= jupiterx_close_markup( 'jupiterx_comment_form[_website]', 'div' );

		$fields['url'] = $url;
	}

	return $fields;
}

jupiterx_add_smart_action( 'comment_form_after_fields', 'jupiterx_comment_form_after_fields', 3 );
/**
 * Echo comment fields closing wraps.
 *
 * This function must be attached to the WordPress 'comment_form_after_fields' action which is only called if
 * the user is not logged in.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_comment_form_after_fields() {
		jupiterx_close_markup_e( 'jupiterx_comment_fields_inner_wrap', 'div' );

	jupiterx_close_markup_e( 'jupiterx_comment_fields_wrap', 'div' );
}
