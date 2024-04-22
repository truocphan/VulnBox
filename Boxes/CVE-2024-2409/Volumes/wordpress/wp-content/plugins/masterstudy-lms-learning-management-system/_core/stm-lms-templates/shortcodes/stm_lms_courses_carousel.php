<?php

$inline = '';
if ( ! empty( $title_color ) ) {
	$title_color = esc_attr( $title_color );
	$inline      = ".{$uniq} .stm_lms_courses_carousel__top h3,
    .{$uniq} .stm_lms_courses_carousel__top h4,
    .{$uniq} .stm_lms_courses_carousel__top .h4 {color: {$title_color};}
    .{$uniq} .stm_lms_courses_carousel__buttons .stm_lms_courses_carousel__button i:before {border-bottom-color: {$title_color};border-left-color: {$title_color}}";
}

if ( empty( $posts_per_page ) ) {
	$posts_per_page = 12;
}
if ( empty( $per_row ) ) {
	$per_row = '6';
}

if ( empty( $pagination ) ) {
	$pagination = 'disable';
}

if ( empty( $loop ) ) {
	$pagination = 'enable';
	$loop       = false;
}

if ( ! empty( $remove_border ) && 'enable' === $remove_border ) {
	$inline .= ".{$uniq} .stm_lms_courses__single__inner {
        border: 0;
    }";
}

$mouse_drag = ! empty( $mouse_drag ) ? $mouse_drag : 'enable';

if ( class_exists( 'STM_LMS_Helpers' ) ) :

	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'owl.carousel' );
	wp_enqueue_style( 'owl.carousel' );
	stm_lms_module_styles( 'courses_carousel', 'style_1', array(), $inline );
	stm_lms_module_scripts( 'courses_carousel', 'style_1' );
	stm_lms_module_scripts( 'image_container', 'card_image' );

	$args = array(
		'per_row'        => intval( $per_row ),
		'posts_per_page' => $posts_per_page,
		'include_link'   => true,
	);

	if ( ! empty( $query ) ) {
		$args = array_merge( $args, STM_LMS_Helpers::sort_query( esc_attr( $query ) ) );
	}

	if ( ! empty( $image_size ) ) {
		$args['image_size'] = esc_attr( $image_size );
	}

	if ( ! empty( $course_card_style ) ) {
		$args['course_card_style'] = esc_attr( $course_card_style );
	}

	if ( ! empty( $course_card_info ) ) {
		$args['course_card_info'] = esc_attr( $course_card_info );
	}

	if ( ! empty( $img_container_height ) ) {
		$args['img_container_height'] = esc_attr( $img_container_height );
	}

	if ( ! empty( $taxonomy_default ) ) {
		$taxonomy_default  = explode( ',', esc_attr( $taxonomy_default ) );
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'stm_lms_course_taxonomy',
				'field'    => 'term_id',
				'terms'    => $taxonomy_default,
			),
		);
	}

	if ( ! empty( $show_categories ) && 'enable' === $show_categories && ! empty( $taxonomy ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'stm_lms_course_taxonomy',
				'hide_empty' => false,
				'include'    => esc_attr( $taxonomy ),
			)
		);
	}

	if ( ! empty( $query ) && 'free' === $query ) {
		$args['meta_query']['free_price'][] = array(
			array(
				'relation' => 'AND',
				array(
					'relation' => 'OR',
					array(
						'key'     => 'price',
						'value'   => array( 0, '' ),
						'compare' => 'in',
					),
					array(
						'key'     => 'price',
						'compare' => 'NOT EXISTS',
					),
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'not_single_sale',
						'value'   => 'on',
						'compare' => '!=',
					),
					array(
						'key'     => 'not_single_sale',
						'compare' => 'NOT EXISTS',
					),
				),
			),
		);
	}

	?>

	<div class="stm_lms_courses_carousel_wrapper
	<?php
	echo esc_attr( $uniq );
	?>
	prev_next_
	<?php
	echo esc_attr( $prev_next_style ?? '' );
	?>
	<?php
	if ( 'disable' === $prev_next ) {
		echo esc_attr( 'no-nav' );
	}
	?>
">
		<div class="stm_lms_courses_carousel"
			data-items="<?php echo esc_attr( $per_row ); ?>"
			data-offset="1"
			data-template="courses/grid"
			data-args='<?php echo wp_json_encode( $args ); ?>'
			data-loop="<?php echo esc_attr( $loop ); ?>"
			data-mouse_drag="<?php echo esc_attr( $mouse_drag ); ?>">

			<?php if ( ! empty( $title ) || ! empty( $terms ) ) : ?>
				<div class="stm_lms_courses_carousel__top">

					<?php if ( ! empty( $title ) ) : ?>
						<h3><?php echo wp_kses_post( $title ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $terms ) ) : ?>
						<div class="stm_lms_courses_carousel__terms">
							<div class="stm_lms_courses_carousel__term active secondary_color">
								<?php
								esc_html_e(
									'All categories',
									'masterstudy-lms-learning-management-system'
								);
								?>
							</div>
							<?php foreach ( $terms as $term ) : ?>
								<div data-term="<?php echo esc_attr( $term->term_id ); ?>"
									class="stm_lms_courses_carousel__term secondary_color"><?php echo esc_attr( $term->name ); ?></div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $view_all_btn_hide_control ) && 'enable' === $view_all_btn_hide_control ) : ?>
						<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>" class="h4">
							<?php
							esc_html_e(
								'View all',
								'masterstudy-lms-learning-management-system'
							);
							?>
							<i class="lnr lnr-arrow-right"></i>
						</a>
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<?php STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) ); ?>

			<?php if ( 'disable' !== $prev_next ) : ?>
				<div class="stm_lms_courses_carousel__buttons">
					<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_prev sbc_h sbrc_h">
						<i class="fa fa-chevron-left"></i>
					</div>
					<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_next sbc_h sbrc_h">
						<i class="fa fa-chevron-right"></i>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
endif;
