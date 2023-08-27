<?php
/**
 * Jupiter Posts Widget.
 *
 * Widget defined here and the options are loading using custom fields.
 *
 * @package JupiterX_Core\Widgets
 *
 * @since 1.0.0
 */

/**
 * Defines new widget as Posts Widget.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX_Core\Widgets
 */
class JupiterX_Widget_Posts extends JupiterX_Widget {
	/**
	 * Setup new widget.
	 */
	public function __construct() {

		$props = [
			'name'        => esc_html__( 'Jupiter X - Posts', 'jupiterx-core' ),
			'description' => esc_html__( 'Custom lists of posts.', 'jupiterx-core' ),
			'settings' => [
				[
					'name'  => 'title',
					'type'  => 'text',
					'label' => esc_html__( 'Title', 'jupiterx-core' ),
				],
				[
					'name'    => 'post_type',
					'type'    => 'select',
					'options' => [
						'post'      => esc_html__( 'Post', 'jupiterx-core' ),
						'portfolio' => esc_html__( 'Portfolio', 'jupiterx-core' ),
					],
					'default' => 'post',
				],
				[
					'name' => 'query_type',
					'type' => 'select',
					'options' => [
						'recent'   => esc_html__( 'Recent', 'jupiterx-core' ),
						'popular'  => esc_html__( 'Popular', 'jupiterx-core' ),
						'comments' => esc_html__( 'Commented', 'jupiterx-core' ),
					],
					'default' => 'recent',
				],
				[
					'name'    => 'category',
					'type'    => 'select2',
					'label'   => esc_html__( 'Category', 'jupiterx-core' ),
					'options' => [],
					'default' => '0',
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'post',
					],
				],
				[
					'name'    => 'portfolio_category',
					'type'    => 'select2',
					'label'   => esc_html__( 'Category', 'jupiterx-core' ),
					'options' => [],
					'default' => '0',
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'portfolio',
					],
				],
				[
					'name'  => 'portfolio_hover',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show portfolio title on hover', 'jupiterx-core' ),
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'portfolio',
					],
				],
				[
					'name'  => 'post_date',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show post date', 'jupiterx-core' ),
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'post',
					],
				],
				[
					'name'  => 'comments_count',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show comment count', 'jupiterx-core' ),
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'post',
					],
				],
				[
					'name'  => 'thumbnail',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show thumbnail', 'jupiterx-core' ),
					'condition' => [
						'setting' => 'post_type',
						'value'   => 'post',
					],
				],
				[
					'name'    => 'posts_num',
					'type'    => 'text',
					'label'   => esc_html__( 'Number of posts to show', 'jupiterx-core' ),
					'default' => '4',
				],
				[
					'name'    => 'order_by',
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'jupiterx-core' ),
					'options' => [
						'date'       => esc_html__( 'Date', 'jupiterx-core' ),
						'title'      => esc_html__( 'Title', 'jupiterx-core' ),
						'menu_order' => esc_html__( 'Menu order', 'jupiterx-core' ),
						'rand'       => esc_html__( 'Random', 'jupiterx-core' ),
					],
					'default' => 'date',
				],
			],
		];

		parent::__construct(
			'jupiterx_posts',
			esc_html__( 'Jupiter X - Posts', 'jupiterx-core' ),
			$props
		);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function widget( $args, $instance ) {
		$defaults = [
			'title'              => '',
			'post_type'          => '',
			'query_type'         => '',
			'portfolio_hover'    => '',
			'post_date'          => '',
			'comments_count'     => '',
			'thumbnail'          => '',
			'posts_num'          => '',
			'order_by'           => '',
			'category'           => [],
			'portfolio_category' => [],
		];

		$instance            = wp_parse_args( $instance, $defaults );
		$title               = $instance['title'];
		$post_type           = $instance['post_type'];
		$query_type          = $instance['query_type'];
		$portfolio_hover     = $instance['portfolio_hover'];
		$show_date           = $instance['post_date'];
		$show_comments_count = $instance['comments_count'];
		$show_thumbnail      = $instance['thumbnail'];
		$post_num            = $instance['posts_num'];
		$orderby             = $instance['order_by'];

		$category_option = 'post' === $post_type ? 'category' : 'portfolio_category';
		$categories      = array_map( function( $category ) {
			return intval( $category );
		}, $instance[ $category_option ] );

		echo $args['before_widget']; // phpcs:ignore

		if ( ! empty( $title ) ) {
			echo $args['before_title']; // phpcs:ignore
			echo esc_html( $title );
			echo $args['after_title']; // phpcs:ignore
		}

		jupiterx_open_markup_e( 'jupiterx_widget_posts_wrapper', 'div', 'class=jupiterx-widget-posts-wrapper' );

		if ( 'comments' === $query_type ) {

			$comments = get_comments( [
				'post_type' => $post_type,
				'number'    => $post_num,
			] );

			$this->comments( $comments, $show_date, $show_comments_count );

		} else {

			$query_args = [
				'post_type'        => $post_type,
				'posts_per_page'   => $post_num,
				'orderby'          => $orderby,
				'suppress_filters' => 0,
			];

			if ( ! in_array( 0, $categories, true ) ) {

				$query_args['tax_query'][] = [ // @codingStandardsIgnoreLine
					'taxonomy' => 'portfolio_category',
					'field'    => 'term_id',
					'terms'    => $categories,
					'operator' => 'IN',
				];

				if ( 'post' === $post_type ) {
					$query_args['cat'] = $categories;
					unset( $query_args['tax_query'] );
				}
			}

			if ( 'popular' === $query_type ) {
				$query_args['orderby'] = 'comment_count';
			}

			$posts = get_posts( $query_args );

			if ( 'post' === $post_type ) {
				$this->posts( $posts, $show_thumbnail, $show_date, $show_comments_count );
			} else {
				$this->portfolios( $posts, $portfolio_hover );
			}
		}

		jupiterx_close_markup_e( 'jupiterx_widget_posts_wrapper', 'div' );

		echo $args['after_widget']; // phpcs:ignore
	}

	/**
	 * Generate portfolio markup in widget.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $posts           Array of post objects.
	 * @param  boolean $portfolio_hover Show title on hover or not. Comes from widget option.
	 *
	 * @return void
	 */
	private function portfolios( $posts, $portfolio_hover ) {

		foreach ( $posts as $post ) {
			if ( ! has_post_thumbnail( $post ) ) {
				continue;
			}

			$portfolio_title = get_the_title( $post );

			jupiterx_open_markup_e( 'jupiterx_widget_posts_portfolio_item', 'div', 'class=jupiterx-widget-posts-portfolio-item' );

				echo get_the_post_thumbnail( $post, 'medium', [ 'data-object-fit' => 'cover' ] );

				if ( $portfolio_hover ) { // phpcs:ignore

					jupiterx_open_markup_e( 'jupiterx_widget_posts_portfolio_title', 'h4', 'class=jupiterx-widget-posts-portfolio-title entry-title jupiterx-thumbnail-over' );

						jupiterx_open_markup_e( 'jupiterx_widget_posts_portfolio_link', 'a', [
							'title' => $portfolio_title,
							'href'  => get_the_permalink( $post ),
						] );

							echo esc_html( $portfolio_title );

						jupiterx_close_markup_e( 'jupiterx_widget_posts_portfolio_link', 'a' );

					jupiterx_close_markup_e( 'jupiterx_widget_posts_portfolio_title', 'h4' );

				} // phpcs:ignore

			jupiterx_close_markup_e( 'jupiterx_widget_posts_portfolio_item', 'div' );
		}
	}

	/**
	 * Generate comments markup in widget.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $comments            Array of comments.
	 * @param  boolean $show_date           Show post date or not. Comes from widget option.
	 * @param  boolean $show_comments_count Show comments count or not. Comes from widget option.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	private function comments( $comments, $show_date, $show_comments_count ) {

		jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_ul', 'ul', 'class=recentcomments' );

		foreach ( $comments as $comment ) {

			$comment_id = $comment->comment_ID;

			jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_li', 'li', 'class=recentcomments' );

				jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_author', 'span', 'class=comment-author-link' );

					echo get_comment_author_link( $comment_id );

				jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_author', 'span' );

				esc_html_e( ' on ', 'jupiterx-core' );

				jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_link', 'a', [
					'href' => get_comment_link( $comment_id ),
					'title' => get_the_title( $comment->comment_post_ID ),
				] );

					echo esc_html( get_the_title( $comment->comment_post_ID ) );

				jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_link', 'a' );

				if ( $show_date || $show_comments_count ) { // phpcs:ignore

					jupiterx_open_markup_e( 'jupiterx_widget_posts_meta', 'div', 'class=jupiterx-widget-posts-meta entry-info' );

						if ( $show_date ) { // phpcs:ignore
							$this->date_markup( $comment_id, $comment->comment_date );
						} // phpcs:ignore

						if ( $show_comments_count ) { // phpcs:ignore
							$this->comments_markup( $comment->comment_post_ID );
						} // phpcs:ignore

					jupiterx_close_markup_e( 'jupiterx_widget_posts_meta', 'div' );

				} // phpcs:ignore

			jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_li', 'li' );

		}

		jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_ul', 'ul' );
	}

	/**
	 * Generate posts markup in widget.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $posts               Array of post objects.
	 * @param  boolean $show_thumbnail      Show thumbnails or not. Comes from widget option.
	 * @param  boolean $show_date           Show post date or not. Comes from widget option.
	 * @param  boolean $show_comments_count Show comments count or not. Comes from widget option.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	private function posts( $posts, $show_thumbnail, $show_date, $show_comments_count ) {

		foreach ( $posts as $post ) {

			$post_title = get_the_title( $post );
			$thumbnail  = has_post_thumbnail( $post ) && $show_thumbnail;
			$class      = $thumbnail ? 'has-thumbnail' : 'no-thumbnail';

			jupiterx_open_markup_e( 'jupiterx_widget_posts_item', 'div', 'class=jupiterx-widget-posts-item ' . $class );

				if ( $thumbnail ) { // phpcs:ignore

					jupiterx_open_markup_e( 'jupiterx_widget_posts_thumbnail', 'div', 'class=jupiterx-widget-posts-image entry-img' );

						echo get_the_post_thumbnail( $post, 'thumbnail', [ 'data-object-fit' => 'cover' ] );

					jupiterx_close_markup_e( 'jupiterx_widget_posts_thumbnail', 'div' );

				} // phpcs:ignore

				jupiterx_open_markup_e( 'jupiterx_widget_posts_main', 'div', 'class=jupiterx-widget-posts-main' );

					jupiterx_open_markup_e( 'jupiterx_widget_posts_title', 'h4', 'class=jupiterx-widget-posts-post-title entry-title' );

						jupiterx_open_markup_e( 'jupiterx_widget_posts_link', 'a', [
							'title' => $post_title,
							'href'  => get_the_permalink( $post ),
						] );

							echo esc_html( $post_title );

						jupiterx_close_markup_e( 'jupiterx_widget_posts_link', 'a' );

					jupiterx_close_markup_e( 'jupiterx_widget_posts_title', 'h4' );

					if ( $show_date || $show_comments_count ) { // phpcs:ignore

						jupiterx_open_markup_e( 'jupiterx_widget_posts_meta', 'div', 'class=jupiterx-widget-posts-meta entry-info' );

							if ( $show_date ) { // phpcs:ignore
								$this->date_markup( $post, get_the_date( 'c', $post ) );
							} // phpcs:ignore

							if ( $show_comments_count ) { // phpcs:ignore
								$this->comments_markup( $post );
							} // phpcs:ignore

						jupiterx_close_markup_e( 'jupiterx_widget_posts_meta', 'div' );

					} // phpcs:ignore

				jupiterx_close_markup_e( 'jupiterx_widget_posts_main', 'div' );

			jupiterx_close_markup_e( 'jupiterx_widget_posts_item', 'div' );

		}
	}

	/**
	 * Markup for comment counts
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID to get comments count for it.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	private function the_comments_num( $post_id ) {

		$comments_count = get_comments_number( $post_id );

		if ( (int) $comments_count === 0 ) { // phpcs:ignore
			_e( 'No Comments', 'jupiterx-core' ); // phpcs:ignore
		} elseif ( $comments_count > 1 ) {
			echo $comments_count . esc_html__( ' Comments', 'jupiterx-core' ); // phpcs:ignore
		} else {
			_e( '1 Comment', 'jupiterx-core' ); // phpcs:ignore
		}
	}

	/**
	 * Comments markup
	 *
	 * @since 1.0.0
	 *
	 * @uses the_comments_num() Used to show comments count.
	 *
	 * @param int $post_id Post ID to get comments count for it.
	 *
	 * @return void
	 */
	private function comments_markup( $post_id ) {

		jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_num', 'span', 'class=jupiterx-widget-posts-comments-num jupiterx-icon-solid-comment' );

			jupiterx_open_markup_e( 'jupiterx_widget_posts_comments_num_text', 'span', 'class=jupiterx-widget-posts-comments-num-text' );

				$this->the_comments_num( $post_id );

			jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_num_text', 'span' );

		jupiterx_close_markup_e( 'jupiterx_widget_posts_comments_num', 'span' );
	}

	/**
	 * Post date markup
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_id  Post ID to get comments count for it.
	 * @param string $iso_date Date time based on ISO 8601.
	 *
	 * @return void
	 */
	private function date_markup( $post_id, $iso_date ) {

		jupiterx_open_markup_e( 'jupiterx_widget_posts_date', 'time', [
			'datetime' => $iso_date,
			'itemprop' => 'datePublished',
			'class'    => 'jupiterx-widget-posts-date entry-date',
		] );

			echo get_the_date( '', $post_id );

		jupiterx_close_markup_e( 'jupiterx_widget_posts_date', 'time' );
	}
}
