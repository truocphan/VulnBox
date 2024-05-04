<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

// require all widgets
foreach ( glob( __DIR__ . '/../controls/*.php' ) as $file ) {
	require_once $file;
}

class Controls_Manager_Piotnetforms {

	/**
	 * @var piotnetforms_Base_Control[]
	 */
	private $controls = [];

	public function __construct() {
		 $this->load_controls();
	}

	public function render() {
		foreach ( $this->controls as $control ) {
			$control->get_template();
		}
	}

	public static function get_control_names() {
		return [
			'text',
			'select',
			'textarea',
			'color',
			'typography',
			'slider',
			'dimensions',
			'media',
			'switch',
			'number',
			'hidden',
			'date',
			'select2',
			'gallery',
			'box-shadow',
			'heading-tab',
			'content-tab',
			'html',
			'repeater',
			'repeater-item',
			'icon',
			'output',
			'tab-widget',
			'division-output',
		];
	}

	private function load_controls() {
		foreach ( self::get_control_names() as $control_id ) {
			$control_class_id = str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $control_id ) ) );
			$control_class_id = str_replace( '-', '_', $control_class_id );
			$class_name       = 'Controls_Piotnetforms\piotnetforms_Control_' . $control_class_id;

			$this->controls[ $control_id ] = new $class_name();
		}
	}
}
