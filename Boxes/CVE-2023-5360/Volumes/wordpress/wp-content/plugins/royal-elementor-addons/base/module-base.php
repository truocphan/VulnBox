<?php
namespace WprAddons\Base;

abstract class Module_Base {

	/**
	 * @var \ReflectionClass
	 */
	private $reflection;

	private $components = [];

	/**
	 * @var Module_Base
	 */
	protected static $_instances = [];

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin huh?', 'wpr-addons' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin huh?', 'wpr-addons' ), '1.0' );
	}

	public static function class_name() {
		return get_called_class();
	}

	/**
	 * @return Module_Base
	 */
	public static function instance() {
		if ( empty( static::$_instances[ static::class_name() ] ) ) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	abstract public function get_name();

	public function get_assets_url() {
		return WPR_ADDONS_MODULES_URL . $this->get_name() . '/assets/';
	}

	public function get_widgets() {
		return [];
	}

	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		// GOGA
		// add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
	}

	public function init_widgets() {
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->reflection->getNamespaceName() .'\Widgets\\'. $widget;
			// $widget_manager->register_widget_type( new $class_name() );
			$widget_manager->register( new $class_name() );
		}
	}

	public function add_component( $id, $instance ) {
		$this->components[ $id ] = $instance;
	}

	public function get_component( $id ) {
		if ( isset( $this->components[ $id ] ) ) {
			return $this->components[ $id ];
		}

		return false;
	}
}
