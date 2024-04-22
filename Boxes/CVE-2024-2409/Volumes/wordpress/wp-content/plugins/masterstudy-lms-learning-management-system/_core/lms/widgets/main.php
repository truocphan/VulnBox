<?php

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly


$widgets = array(
	'popular'
);

foreach($widgets as $widget) {
	require_once STM_LMS_PATH . "/lms/widgets/{$widget}.widget.php";
}


add_action( 'widgets_init', 'stm_lms_widgets_init' );

function stm_lms_widgets_init() {
	register_sidebar( array(
		'name' => __( 'STM LMS Sidebar', 'masterstudy-lms-learning-management-system' ),
		'id' => 'stm_lms_sidebar',
		'description' => __( 'Widgets in this area will be shown on STM LMS Pages.', 'masterstudy-lms-learning-management-system' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div><div class="multiseparator"></div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
}