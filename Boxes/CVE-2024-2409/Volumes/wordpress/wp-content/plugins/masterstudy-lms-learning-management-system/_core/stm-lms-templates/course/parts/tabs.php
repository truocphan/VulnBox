<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>


<?php
$tabs                 = array();
$tabs['description']  = esc_html__( 'Description', 'masterstudy-lms-learning-management-system' );
$tabs['curriculum']   = esc_html__( 'Curriculum', 'masterstudy-lms-learning-management-system' );
$tabs['faq']          = esc_html__( 'FAQ', 'masterstudy-lms-learning-management-system' );
$tabs['announcement'] = esc_html__( 'Announcement', 'masterstudy-lms-learning-management-system' );
$tabs['reviews']      = esc_html__( 'Reviews', 'masterstudy-lms-learning-management-system' );
$tabs                 = apply_filters( 'stm_lms_course_tabs', $tabs, get_the_ID() );

$active      = array_search( reset( $tabs ), $tabs, true );
$tabs_length = count( $tabs );
$tab_attr    = 'Divi' === wp_get_theme()->get_template() ? '' : '#';
?>

<?php if ( $tabs_length > 0 ) : ?>
<div class="nav-tabs-wrapper">
	<ul class="nav nav-tabs" role="tablist">

		<?php foreach ( $tabs as $slug => $name ) : ?>
			<li role="presentation" class="<?php echo ( $slug === $active ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_attr( $tab_attr . $slug ); ?>"
					data-toggle="tab">
					<?php echo wp_kses_post( $name ); ?>
				</a>
			</li>
		<?php endforeach; ?>

	</ul>
</div>
<?php endif; ?>

<div class="tab-content">
	<?php foreach ( $tabs as $slug => $name ) : ?>
		<div role="tabpanel"
			class="tab-pane <?php echo ( $slug === $active ) ? 'active' : ''; ?> "
			id="<?php echo esc_attr( $slug ); ?>">
			<?php STM_LMS_Templates::show_lms_template( 'course/parts/tabs/' . $slug ); ?>
		</div>
	<?php endforeach; ?>
</div>
