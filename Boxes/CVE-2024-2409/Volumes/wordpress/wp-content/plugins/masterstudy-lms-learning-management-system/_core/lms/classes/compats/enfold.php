<?php


new STM_LMS_Enfold();

class STM_LMS_Enfold
{
	public function __construct()
	{
		/*Lesson header fix*/
		add_action('init', function () {
			if (function_exists('avia_get_option')) {
				add_filter('stm_lms_lesson_html_classes', array($this, 'html_classes'));
			}
		});
	}

	function html_classes($classes)
	{
		$classes[] = avia_get_option('responsive_active') != 'disabled' ? 'responsive' : 'fixed_layout';

		return $classes;
	}

}
