<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} //Exit if accessed directly ?>


<?php
$tabs = array(
	'description'  => esc_html__( 'Description', 'masterstudy-lms-learning-management-system-pro' ),
	'curriculum'   => esc_html__( 'Curriculum', 'masterstudy-lms-learning-management-system-pro' ),
	'faq'          => esc_html__( 'FAQ', 'masterstudy-lms-learning-management-system-pro' ),
	'announcement' => esc_html__( 'Announcement', 'masterstudy-lms-learning-management-system-pro' ),
	'reviews'      => esc_html__( 'Reviews', 'masterstudy-lms-learning-management-system-pro' ),
);

$tabs        = apply_filters( 'stm_lms_course_tabs', $tabs, get_the_ID() );
$active      = array_search( reset( $tabs ), $tabs ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
$tabs_length = count( $tabs );
?>


<?php if ( $tabs_length > 1 ) : ?>
	<ul class="nav nav-tabs" role="tablist">

		<?php foreach ( $tabs as $slug => $name ) : ?>
			<li role="presentation" class="<?php echo ( $slug === $active ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_attr( $slug ); ?>" class="sbc" data-toggle="tab">
					<?php echo wp_kses_post( $name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<div class="tab-content">
	<?php foreach ( $tabs as $slug => $name ) : ?>
		<div role="tabpanel" class="tab-pane  <?php echo ( $slug === $active ) ? 'active' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>">
			<?php STM_LMS_Templates::show_lms_template( 'course/udemy/parts/tabs/' . $slug ); ?>
		</div>
	<?php endforeach; ?>
</div>
