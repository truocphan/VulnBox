<?php

namespace JupiterX_Core\Raven\Modules\Product_Reviews\Classes;

class Jupiterx_Product_Review_Content {

	public function __construct( $product_id, $settings ) {
		$this->settings    = $settings;
		$this->id          = $product_id;
		$this->product     = wc_get_product( $product_id );
		$this->count       = $this->product->get_review_count();
		$this->has_comment = true;
		$this->test_mode   = false;
	}

	/**
	 * Print comments.
	 *
	 * @since 2.5.0
	 */
	public function comments() {
		$args = [
			'post_id' => $this->id,
			'type'    => 'review',
		];

		$comments_query = new \WP_Comment_Query();
		$comments       = $comments_query->query( $args );
		$this->count    = count( $comments );

		if ( $this->count < 1 && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->has_comment = false;
			return;
		}

		if ( 0 === count( $comments ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$comments        = $this->dummy_data();
			$this->test_mode = true;

			echo '<div class="elementor-alert elementor-alert-danger">' . sprintf(
				esc_html__( 'There is no review for this product. Dummy reviews are loaded in editor mode for demonstration purposes.', 'jupiterx-core' )
			) . '</div>';
		}

		echo '<h3 class="jupiterx-product-review-header">' . sprintf(
			/* Translators: 1 : count of review 2 : product name */
			esc_html__( '%1$s reviews for %2$s', 'jupiterx-core' ),
			( true === $this->test_mode ) ? count( $comments ) : $this->count,
			( ! empty( $this->product->get_name() ) ) ? $this->product->get_name() : esc_html__( 'The Product', 'jupiterx-core' )
		) . '</h3>';

		echo '<div class="jupiterx-product-review-singles-wrapper" >';

		foreach ( $comments as $comment ) {
			if ( true === $this->test_mode ) {
				$comment = (object) $comment;
			}

			$this->single_review( $comment );
		}

		echo '</div>';
	}

	/**
	 * Single review.
	 *
	 * @since 2.5.0
	 */
	private function single_review( $comment ) {
		$this->single_comment( $comment );

		if ( true === $this->test_mode ) {
			return;
		}

		$comment_id = $comment->comment_ID;
		$args       = [
			'post_id' => $this->id,
			'type'    => 'comment',
			'parent'  => $comment_id,
		];

		$comments_query = new \WP_Comment_Query();
		$comments       = $comments_query->query( $args );

		if ( 0 === count( $comments ) ) {
			return;
		}

		foreach ( $comments as $comment ) {
			$this->single_review( $comment );
		}
	}

	/**
	 * Single comment html.
	 *
	 * @param object $comment single comment info.
	 * @since 2.5.0
	 */
	public function single_comment( $comment ) {
		$author_id       = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : $comment->user_id;
		$comment_author  = $comment->comment_author;
		$comment_date    = $comment->comment_date;
		$comment_content = $comment->comment_content;
		$class           = 'jupiterx-product-review-single';

		if ( empty( $comment->rating ) && empty( $comment->verify ) ) {
			$comment->rating = get_comment_meta( $comment->comment_ID, 'rating', true );
			$comment->verify = get_comment_meta( $comment->comment_ID, 'verified', true );
		}

		if ( 'comment' === $comment->comment_type ) {
			$comment->rating = 5;
			$class          .= ' jupiterx-product-review-single-comment';
		}

		if ( false === $this->test_mode ) {
			$comment_date = strtotime( $comment_date );
		}

		?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<div class="jupiterx-product-review-single-left">
					<?php echo get_avatar( $author_id ); ?>
				</div>
				<div class="jupiterx-product-review-single-right">
					<?php if ( 'review' === $comment->comment_type ) : ?>
					<div class="jupiterx-product-review-single-ratings">
						<?php $this->rating( $comment->rating ); ?>
					</div>
					<?php endif; ?>
					<div class="jupiterx-product-review-single-author">
						<span><?php echo esc_html( $comment_author ); ?></span>
					</div>
					<div class="jupiterx-product-review-single-date">
						<span>
							<?php echo date( 'M d, Y, H:i A', $comment_date ); ?>
						</span>
					</div>
					<div class="jupiterx-product-review-single-content jx-product-review-secondary-text">
						<p><?php echo wp_kses_post( $comment_content ); ?></p>
					</div>
				</div>
			</div>
		<?php
	}

	public function rating( $rating ) {
		for ( $i = 1; $i <= $rating; $i++ ) {
			echo '<label class="jupiterx-product-review-marked"></label>';
		}

		$unmarked = 5 - $rating;

		for ( $i = 1; $i <= $unmarked; $i++ ) {
			echo '<label class="jupiterx-product-review-unmarked"></label>';
		}
	}

	public function form() {
		$logged_in = false;

		if ( is_user_logged_in() ) {
			$logged_in = true;
			$user      = wp_get_current_user();
		}

		?>
			<div class="jupiterx-product-review-form-wrapper">
				<h3 class="jupiterx-product-review-header"><?php esc_html_e( 'Add a Review', 'jupiterx-core' ); ?></h3>
				<?php if ( false === $this->has_comment ) : ?>
				<span class="jupiterx-product-review-sub-headers jx-product-review-secondary-text">
					<?php esc_html_e( 'There are no reviews yet.', 'jupiterx-core' ); ?>
				</span>
				<span class="jupiterx-product-review-sub-headers jx-product-review-secondary-text">
					<?php
						echo sprintf(
							/* Translators: 1: Product name */
							esc_html__( 'Be the first to review "%s"', 'jupiterx-core' ),
							$this->product->get_name()
						);
					?>
				</span>
				<?php endif; ?>
				<span class="jupiterx-product-review-sub-headers jx-sub-header-marked-pr jx-product-review-secondary-text">
					<?php esc_html_e( 'Your email address will not be published. Required fields are marked', 'jupiterx-core' ); ?>
				</span>
				<div class="jupiterx-product-review-form">
					<div class="jupiterx-product-review-form-stars jupiterx-product-review-form-subs">
						<h5><?php esc_html_e( 'Your rating', 'jupiterx-core' ); ?></h5>
						<div class="jx-stars-rating" >
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<label class="jupiterx-product-review-rating-selector jupiterx-product-review-unmarked" data-rate="<?php echo esc_attr( $i ); ?>"></label>
							<?php endfor; ?>
						</div>
						<input type="hidden" id="jupiterx-product-review-input-rating">
						<span class="jupiterx-product-review-alarm">
							<?php esc_html_e( 'Please select rating.', 'jupiterx-core' ); ?>
						</span>
					</div>
					<div class="jupiterx-product-review-form-review jupiterx-product-review-form-subs">
						<h5><?php esc_html_e( 'Your review', 'jupiterx-core' ); ?></h5>
						<textarea class="jupiterx-product-review-textarea"></textarea>
						<?php $this->required_alarm(); ?>
					</div>
					<?php if ( ! $logged_in ) : ?>
					<div class="jupiterx-product-review-form-name jupiterx-product-review-form-subs">
						<h5><?php esc_html_e( 'Name', 'jupiterx-core' ); ?></h5>
						<input type="text" class="jupiterx-product-review-name" >
						<?php $this->required_alarm(); ?>
					</div>
					<div class="jupiterx-product-review-form-email jupiterx-product-review-form-subs">
						<h5><?php esc_html_e( 'Email', 'jupiterx-core' ); ?></h5>
						<input type="email" class="jupiterx-product-review-email" >
						<?php $this->required_alarm(); ?>
					</div>
					<div class="jupiterx-product-review-form-acceptance jupiterx-product-review-form-subs">
						<input type="checkbox" id="jupiterx-product-review-acceptance">
						<label for="jupiterx-product-review-acceptance" class="jx-product-review-secondary-text">
							<?php esc_html_e( 'Save my name and email in this browser for the next time I comment.', 'jupiterx-core' ); ?>
						</label>
					</div>
					<?php endif; ?>
					<p class="jupiterx-product-review-global-error">
						<?php esc_html_e( 'Something went wrong, we could not submit review.', 'jupiterx-core' ); ?>
					</p>
					<div class="jupiterx-product-review-form-submit jupiterx-product-review-form-subs">
						<input type="hidden" id="jupiterx-product-review-related" value="<?php echo esc_attr( $this->id ); ?>" >
						<?php if ( $logged_in ) : ?>
							<input type="hidden" value="<?php echo esc_attr( $user->user_email ); ?>" class="jupiterx-product-review-email">
							<input type="hidden" value="<?php echo esc_attr( $user->display_name ); ?>" class="jupiterx-product-review-name">
						<?php endif; ?>
						<button class="jupiterx-product-review-submit-new"><?php esc_html_e( 'Submit', 'jupiterx-core' ); ?></button>
					</div>
				</div>
			</div>
		<?php
	}

	private function required_alarm() {
		?>
			<span class="jupiterx-product-review-alarm">
				<?php esc_html_e( 'This field is required.', 'jupiterx-core' ); ?>
			</span>
		<?php
	}

	private function dummy_data() {
		return [
			[
				'rating'          => 5,
				'verify'          => 1,
				'user_id'         => 1,
				'comment_author'  => 'Alice Peterson',
				'comment_date'    => time(),
				'comment_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
				'comment_type'    => 'review',
			],
			[
				'rating'          => 4,
				'verify'          => 1,
				'user_id'         => 1,
				'comment_author'  => 'Jessica Moris',
				'comment_date'    => time(),
				'comment_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
				'comment_type'    => 'review',
			],
			[
				'rating'          => 3,
				'verify'          => 1,
				'user_id'         => 1,
				'comment_author'  => 'Bruce',
				'comment_date'    => time(),
				'comment_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
				'comment_type'    => 'review',
			],
			[
				'rating'          => 2,
				'verify'          => 1,
				'user_id'         => 1,
				'comment_author'  => 'Will Williams',
				'comment_date'    => time(),
				'comment_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
				'comment_type'    => 'review',
			],
			[
				'rating'          => 1,
				'verify'          => 1,
				'user_id'         => 1,
				'comment_author'  => 'James B Clinton',
				'comment_date'    => time(),
				'comment_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
				'comment_type'    => 'review',
			],
		];
	}
}
