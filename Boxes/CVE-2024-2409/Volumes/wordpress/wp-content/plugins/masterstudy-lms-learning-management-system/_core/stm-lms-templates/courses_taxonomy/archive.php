<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<?php
$term           = get_queried_object();
$filter_enabled = STM_LMS_Courses::filter_enabled();
stm_lms_register_style( 'taxonomy_archive' );

$args = array(
	'per_row'        => STM_LMS_Options::get_option( 'courses_per_row', 4 ),
	'posts_per_page' => STM_LMS_Options::get_option( 'courses_per_page', get_option( 'posts_per_page' ) ),
	'tax_query'      => array(
		array(
			'taxonomy' => 'stm_lms_course_taxonomy',
			'field'    => 'term_id',
			'terms'    => $term->term_id,
		),
	),
	'class'          => 'archive_grid',
);

?>

<h2><?php echo esc_html( $term->name ); ?></h2>

<?php if ( ! empty( $term->description ) ) : ?>
	<p>
		<?php echo wp_kses_post( $term->description ); ?>
	</p>
<?php endif; ?>

<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>

<div class="stm_lms_courses_wrapper">
	<div class="stm_lms_courses__archive_wrapper">
	<?php
	if ( $filter_enabled ) {
		stm_lms_register_style( 'courses_filter' );
		stm_lms_register_script( 'courses_filter' );

		STM_LMS_Templates::show_lms_template(
			'courses/advanced_filters/main',
			array(
				'args'     => $args,
				'category' => $term->term_id,
			)
		);
	}
	?>
		<div class="stm_lms_courses stm_lms_courses__archive">
			<?php
			STM_LMS_Templates::show_lms_template(
				'courses/grid',
				array(
					'args' => $args,
				)
			);
			STM_LMS_Templates::show_lms_template(
				'courses/load_more',
				array(
					'args' => $args,
				)
			);
			?>
		</div>
	</div>
</div>
