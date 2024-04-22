<?php
$user_args = array(
	'role'   => STM_LMS_Instructor::role(),
	'number' => -1,
);

if ( ! empty( $sort ) && 'rating' === $sort ) {
	$sort_args = array(
		'meta_key' => 'average_rating',
		'orderby'  => 'meta_value_num',
		'order'    => 'DESC',
	);

	$user_args = array_merge( $user_args, $sort_args );
}

$user_query = new WP_User_Query( $user_args );
$results    = $user_query->get_results();

foreach ( $results as $result_index => &$result ) {
	$result->lms_data = STM_LMS_User::get_current_user( $result->ID );
}

$template = STM_LMS_Templates::load_lms_template( 'multi_instructor/add_teacher_template' );

stm_lms_register_style( 'add_teacher_component' );
stm_lms_pro_register_script( 'add_teacher_component', array( 'stm-lms-manage_course' ) );

wp_localize_script(
	'stm-lms-add_teacher_component',
	'stm_lms_add_teacher',
	array(
		'template' => $template,
		'users'    => $results,
	)
);


if ( ! empty( $results ) ) :
	?>
	<stm_lms_add_teacher v-bind:instructor_id="fields['co_instructor']" v-on:get_co_instructor="Vue.set(fields, 'co_instructor', $event)"></stm_lms_add_teacher>
	<?php
endif;
