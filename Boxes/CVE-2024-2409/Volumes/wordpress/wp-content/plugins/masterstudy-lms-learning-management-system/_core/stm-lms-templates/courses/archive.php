<?php
$filter_enabled  = STM_LMS_Courses::filter_enabled();
$default_per_row = $filter_enabled ? 3 : 4;
$args            = apply_filters(
	'stm_lms_archive_filter_args',
	array(
		'image_size'     => STM_LMS_Options::get_option( 'courses_image_size', '480x380' ),
		'per_row'        => STM_LMS_Options::get_option( 'courses_per_row', $default_per_row ),
		'posts_per_page' => STM_LMS_Options::get_option( 'courses_per_page', get_option( 'posts_per_page' ) ),
		'class'          => 'archive_grid',
	)
);
?>

<div class="stm_lms_courses_wrapper">

	<?php
	STM_LMS_Templates::show_lms_template(
		'courses/filters',
		array( 'args' => $args )
	);
	?>

	<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

	<div class="stm_lms_courses__archive_wrapper">

		<?php
		if ( $filter_enabled ) :
			stm_lms_register_style( 'courses_filter' );
			stm_lms_register_script( 'courses_filter' );
			?>

			<?php
			STM_LMS_Templates::show_lms_template(
				'courses/advanced_filters/main',
				array( 'args' => $args )
			);
			?>

		<?php endif; ?>

		<div class="stm_lms_courses stm_lms_courses__archive <?php echo ( $filter_enabled ) ? 'filter_enabled' : ''; ?>">

			<?php
			$meta_query    = ! empty( $args['meta_query'] ) ? $args['meta_query'] : array();
			$show_featured = true;
			foreach ( $meta_query as $query ) {
				if ( is_array( $query ) ) {
					foreach ( $query as $query_item ) {
						if ( is_array( $query_item ) && ! empty( $query_item['key'] ) && 'featured' === $query_item['key'] ) {
							$show_featured = false;
						}
					}
				}
			}
			if ( $show_featured && ! STM_LMS_Options::get_option( 'disable_featured_courses', false ) ) {
				$per_row            = STM_LMS_Options::get_option( 'courses_per_row', 3 );
				$number_of_featured = STM_LMS_Options::get_option( 'number_featured_in_archive', $per_row );
				if ( ! empty( $number_of_featured ) && 0 !== $number_of_featured ) {
					$featured_args                   = $args;
					$featured_args['class']         .= ' featured-courses';
					$featured_args['is_featured']    = true;
					$featured_args['orderby']        = 'rand';
					$featured_args['posts_per_page'] = intval( $number_of_featured );

					if ( empty( $featured_args['meta_query'] ) ) {
						$featured_args['meta_query'] = array(
							array(
								'key'   => 'featured',
								'value' => 'on',
							),
						);
					} elseif ( ! empty( $featured_args['meta_query']['status'] ) ) {
						$featured_args['meta_query']['status'][] = array(
							'key'   => 'featured',
							'value' => 'on',
						);
					} else {
						$featured_args['meta_query']['status'] = array(
							array(
								'key'   => 'featured',
								'value' => 'on',
							),
						);
					}
					STM_LMS_Templates::show_lms_template(
						'courses/grid',
						array( 'args' => $featured_args )
					);
				}
			}
			?>

			<?php
			STM_LMS_Templates::show_lms_template(
				'courses/grid',
				array( 'args' => $args )
			);
			?>

			<?php
			STM_LMS_Templates::show_lms_template(
				'courses/load_more',
				array( 'args' => $args )
			);
			?>

		</div>

	</div>

</div>
