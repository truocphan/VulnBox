<?php

/**
 * @var int $course_id
 * @var int $lesson_id
 * @var boolean $dark_mode
 *
 * .masterstudy-discussions_dark-mode - for dark mode
 */

wp_enqueue_style( 'masterstudy-discussions' );
wp_enqueue_script( 'masterstudy-discussions' );
wp_localize_script(
	'masterstudy-discussions',
	'discussions_data',
	array(
		'get_nonce'            => wp_create_nonce( 'stm_lms_get_comments' ),
		'add_nonce'            => wp_create_nonce( 'stm_lms_add_comment' ),
		'ajax_url'             => admin_url( 'admin-ajax.php' ),
		'course_id'            => $course_id,
		'lesson_id'            => $lesson_id,
		'textarea_placeholder' => __( 'Enter message', 'masterstudy-lms-learning-management-system' ),
		'cancel_title'         => __( 'Cancel', 'masterstudy-lms-learning-management-system' ),
		'reply_title'          => __( 'Reply', 'masterstudy-lms-learning-management-system' ),
		'not_items_title'      => __( 'No discussions yet...', 'masterstudy-lms-learning-management-system' ),
		'not_items_subtitle'   => __( 'Here you can ask a question or discuss a topic', 'masterstudy-lms-learning-management-system' ),
	)
);
?>

<div class="masterstudy-discussions <?php echo esc_attr( $dark_mode ? 'masterstudy-discussions_dark-mode' : '' ); ?>">
	<div class="masterstudy-discussions__header">
		<div class="masterstudy-discussions__search" style="display:none;">
			<div class="masterstudy-discussions__input-wrapper">
				<input
					type="text"
					id="masterstudy-discussions-search"
					name="masterstudy-discussions-search"
					class="masterstudy-discussions__input"
					placeholder="<?php echo esc_attr__( 'Search', 'masterstudy-lms-learning-management-system' ); ?>"
				>
				<span class="masterstudy-discussions__search-close"></span>
			</div>
			<span class="masterstudy-discussions__search-add"></span>
		</div>
		<div class="masterstudy-discussions__send" style="display:none;">
			<div class="masterstudy-discussions__send-comment">
				<textarea
					name="masterstudy-discussions-comment-textarea"
					class="masterstudy-discussions__textarea"
					placeholder="<?php echo esc_attr__( 'Enter message', 'masterstudy-lms-learning-management-system' ); ?>"
					rows="3"
				></textarea>
				<div class="masterstudy-discussions__send-wrapper">
					<span class="masterstudy-discussions__send-button"></span>
				</div>
			</div>
			<span class="masterstudy-discussions__cancel">
				<?php echo esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</div>
		<div class="masterstudy-discussions__header-wrapper">
			<span class="masterstudy-discussions__search-button"></span>
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'            => 'masterstudy-discussions-add-comment',
					'title'         => __( 'Comment', 'masterstudy-lms-learning-management-system' ),
					'link'          => '',
					'style'         => 'tertiary',
					'size'          => 'sm',
					'icon_position' => 'left',
					'icon_name'     => 'plus',
				)
			);
			?>
		</div>
	</div>
	<div class="masterstudy-discussions__content"></div>
	<div class="masterstudy-discussions__navigation">
		<span class="masterstudy-discussions__load-more">
			<span class="masterstudy-discussions__load-more-title">
				<?php echo esc_html__( 'Load more', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</span>
	</div>
</div>
