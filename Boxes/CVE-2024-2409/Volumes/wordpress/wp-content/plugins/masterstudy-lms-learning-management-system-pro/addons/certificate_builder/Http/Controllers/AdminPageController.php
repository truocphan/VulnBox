<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder\Http\Controllers;

class AdminPageController {
	public function __invoke(): void {
		$this->enqueue_scripts();

		$translations = array(
			'text'         => esc_html__( 'Text', 'masterstudy-lms-learning-management-system-pro' ),
			'course_name'  => esc_html__( 'Course name', 'masterstudy-lms-learning-management-system-pro' ),
			'student_name' => esc_html__( 'Student name', 'masterstudy-lms-learning-management-system-pro' ),
			'image'        => esc_html__( 'Image', 'masterstudy-lms-learning-management-system-pro' ),
			'author'       => esc_html__( 'Author', 'masterstudy-lms-learning-management-system-pro' ),
		);

		wp_localize_script( 'stm_certificate_builder', 'stm_translations', $translations );

		// todo: replace include by template loader
		include STM_LMS_PRO_ADDONS . '/certificate_builder/templates/main.php';
	}

	private function enqueue_scripts(): void {

		wp_enqueue_style(
			'stm_certificate_builder',
			STM_LMS_URL . '/assets/css/parts/certificate_builder/main.css',
			array(),
			stm_lms_custom_styles_v()
		);
		wp_enqueue_style(
			'stm_certificate_fonts',
			'https://fonts.googleapis.com/css?family=Katibeh|Amiri|Merriweather:400,700|Montserrat:400,700|Open+Sans:400,700|Oswald:400,700',
			array(),
			stm_lms_custom_styles_v()
		);

		wp_enqueue_script(
			'stm_generate_certificate',
			STM_LMS_URL . '/assets/js/certificate_builder/generate_certificate.js',
			array(
				'jquery',
				'jspdf',
				'stm_certificate_fonts',
			),
			stm_lms_custom_styles_v(),
			true
		);
		wp_enqueue_script(
			'stm_certificate_builder',
			STM_LMS_URL . '/assets/js/certificate_builder/main.js',
			array(
				'jquery',
				'vue.js',
				'vue-resource.js',
			),
			stm_lms_custom_styles_v(),
			true
		);
		wp_enqueue_script(
			'vue2-color.js',
			STM_LMS_URL . '/nuxy/metaboxes/assets/js/vue-color.min.js',
			array(
				'jquery',
				'vue.js',
				'vue-resource.js',
			),
			stm_lms_custom_styles_v(),
			true
		);
	}
}
