<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Base extends piotnetforms_Variables {

	public $settings;
	private $post_id;
	private $additional_attributes;
	public $widget_id;
	private $editor;

	public function __construct() {
		parent::__construct();
		$this->post_id = get_the_ID();

		if ( method_exists($this, 'get_script') ){
            $scripts = $this->get_script();
            if ( !empty($scripts) ) {
            	foreach ($scripts as $script) {
            		wp_enqueue_script( $script );
            	}
            }
        }

        if ( method_exists($this, 'get_style') ){
            $styles = $this->get_style();
            if ( !empty($styles) ) {
            	foreach ($styles as $style) {
            		wp_enqueue_style( $style );
            	}
            }
        }
	}

	public function get_id() {
		return $this->widget_id;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function widget_visibility() {
		$settings   = $this->settings;
		$visibility = true;
		if ( ! empty( $settings['conditional_visibility_enable'] ) ) {
			if ( 'yes' === $settings['conditional_visibility_enable'] ) {
				$visibility_roles = $settings['conditional_visibility_roles'];
				$condition1       = false;
				$user             = wp_get_current_user();
				$user_roles       = $user->roles;
				if ( in_array( 'all', $visibility_roles ) || in_array( 'logged_in', $visibility_roles ) && is_user_logged_in() || in_array( 'non_logged_in', $visibility_roles ) && ! is_user_logged_in() ) {
					$condition1 = true;
				}

				if ( isset( $user_roles[0] ) ) {
					if ( in_array( $user_roles[0], $visibility_roles ) ) {
						$condition1 = true;
					}
				}

				$condition2 = true;

				$show = 'show';

				if ( ! empty( $settings['conditional_visibility_by_backend'] ) ) {
					if ( array_key_exists( 'conditional_visibility_by_backend_list', $settings ) ) {
						$list = $settings['conditional_visibility_by_backend_list'];
						$show = $settings['conditional_visibility_action'];

						if ( ! empty( $list[0]['conditional_visibility_by_backend_select'] ) ) {
							$conditionals_count  = count( $list );
							$conditionals_and_or = '';
							$error               = 0;
							$condition           = false;
							foreach ( $list as $item ) {
								$conditionals_and_or = $item['conditional_visibility_and_or_operators'];
								if ( $item['conditional_visibility_by_backend_select'] === 'custom_field' && ! empty( $item['conditional_visibility_custom_field_key'] ) ) {

									$field_key        = $item['conditional_visibility_custom_field_key'];
									$field_source     = $item['conditional_visibility_custom_field_source'];
									$field_value      = '';
									$comparison       = $item['conditional_visibility_custom_field_comparison_operators'];
									$comparison_value = isset( $item['conditional_visibility_custom_field_value'] ) ? $item['conditional_visibility_custom_field_value'] : '';
									$id               = get_the_ID();

									if ( $field_source === 'post_custom_field' ) {
										$field_value = get_post_meta( $id, $field_key, true );
									} else {
										if ( function_exists( 'get_field' ) ) {
											$field_value = get_field( $field_key, $id );
										}
									}

									if ( isset( $item['conditional_visibility_custom_field_type'] ) ) {
										if ( $item['conditional_visibility_custom_field_type'] === 'number' ) {
											$field_value = floatval( $field_value );
										}
									}

									if ( is_array( $field_value ) && $comparison === 'contains' ) {
										if ( in_array( $comparison_value, $field_value ) ) {
											$condition = true;
										} else {
											$error++;
										}
									} else {
										if ( $comparison === 'not-empty' && ! empty( $field_value ) || $comparison === 'empty' && empty( $field_value ) || $comparison === 'true' && $field_value === true || $comparison === 'false' && $field_value === false || $comparison === '=' && $field_value === $comparison_value || $comparison === '!=' && $field_value !== $comparison_value || $comparison === '>' && $field_value > $comparison_value || $comparison === '>=' && $field_value >= $comparison_value || $comparison === '<' && $field_value < $comparison_value || $comparison === '<=' && $field_value <= $comparison_value ) {
											$condition = true;
										} else {
											$error++;
										}
									}
								}

								if ( $item['conditional_visibility_by_backend_select'] === 'url_parameter' && ! empty( $item['conditional_visibility_url_parameter'] ) ) {

									$url_parameter    = $item['conditional_visibility_url_parameter'];
									$comparison       = $item['conditional_visibility_custom_field_comparison_operators'];
									$comparison_value = isset( $item['conditional_visibility_custom_field_value'] ) ? $item['conditional_visibility_custom_field_value'] : '';
									$field_value      = '';

									if ( ! empty( $_GET[ $url_parameter ] ) ) {
										$field_value = sanitize_text_field($_GET[ $url_parameter ]);
									}

									if ( isset( $item['conditional_visibility_custom_field_type'] ) ) {
										if ( $item['conditional_visibility_custom_field_type'] === 'number' ) {
											$field_value = floatval( $field_value );
										}
									}

									if ( $comparison === 'not-empty' && ! empty( $field_value ) || $comparison === 'empty' && empty( $field_value ) || $comparison === 'true' && $field_value === true || $comparison === 'false' && $field_value === false || $comparison === '=' && $field_value === $comparison_value || $comparison === '!=' && $field_value !== $comparison_value || $comparison === '>' && $field_value > $comparison_value || $comparison === '>=' && $field_value >= $comparison_value || $comparison === '<' && $field_value < $comparison_value || $comparison === '<=' && $field_value <= $comparison_value ) {
										$condition = true;
									} else {
										$error++;
									}
								}

								if ( $item['conditional_visibility_by_backend_select'] === 'url_contains' && ! empty( $item['conditional_visibility_custom_field_value_url_contains'] ) ) {

									$url_contains = $item['conditional_visibility_custom_field_value_url_contains'];
									$actual_link  = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
									$find         = strpos( $actual_link, $url_contains );

									if ( $find !== false ) {
										$condition = true;
									} else {
										$error++;
									}
								}
							}

							if ( $conditionals_and_or === 'or' ) {
								if ( $conditionals_count <= $error ) {
									$condition2 = false;
								}
							}

							if ( $conditionals_and_or === 'and' ) {
								if ( $error !== 0 ) {
									$condition2 = false;
								}
							}
						}
					}
				}

				if ( $condition1 === true && $condition2 === true && $show === 'show' ) {
					$visibility = true;
				} elseif ( $condition1 === true && $condition2 === false && $show === 'hide' ) {
					$visibility = true;
				} else {
					$visibility = false;
				}
			} else {
				$visibility = true;
			}
		}

		return $visibility;
	}

	public function output_wrapper_start( $widget_id = '', $editor = false ) {
		ob_start();

		$widget_information = [
			'type'       => $this->get_type(),
			'class_name' => $this->get_class_name(),
			'title'      => $this->get_title(),
			'icon'       => $this->get_icon(),
		];

		$settings = $this->settings;

		$visibility = true;

		if ( ! $editor ) {
			$visibility = $this->widget_visibility();
		}

		if ( $editor ) {
			$this->add_render_attribute( 'widget_wrapper', 'class', 'piotnet-widget' );
			$this->add_render_attribute( 'widget_wrapper', 'data-piotnet-editor-widgets-item', json_encode( $widget_information ) );
			$this->add_render_attribute( 'widget_wrapper', 'data-piotnet-editor-widgets-item-id', $widget_id );

			if ( $this->get_type() === 'section' ) {
				$this->add_render_attribute( 'widget_wrapper', 'data-piotnet-editor-widgets-item-section', '' );
				$this->add_render_attribute( 'widget_wrapper', 'data-piotnet-editor-section', '' );
				$this->add_render_attribute( 'widget_wrapper', 'draggable', 'true' );
			}

			if ( $this->get_type() === 'column' ) {
				$this->add_render_attribute( 'widget_wrapper', 'data-piotnet-editor-column', '' );
			}
		}

		if ( $this->get_type() === 'section' ) {
			$this->add_render_attribute( 'widget_wrapper', 'class', 'piotnet-section' );
		}

		if ( $this->get_type() === 'column' ) {
			$this->add_render_attribute( 'widget_wrapper', 'class', 'piotnet-column' );
		}

		if ( $this->get_type() === 'section' || $this->get_type() === 'column' ) {
			$this->add_render_attribute( 'widget_wrapper', 'class', $this->widget_id );

			if ( ! empty( $settings['advanced_custom_id'] ) ) {
				$this->add_render_attribute( 'widget_wrapper', 'id', $settings['advanced_custom_id'] );
			}

			if ( ! empty( $settings['advanced_custom_classes'] ) ) {
				$this->add_render_attribute( 'widget_wrapper', 'class', $settings['advanced_custom_classes'] );
			}

			if ( ! empty( $settings['piotnetforms_repeater_enable'] ) && ! empty( $settings['piotnetforms_repeater_form_id'] ) && ! empty( $settings['piotnetforms_repeater_id'] ) && ! empty( $settings['piotnetforms_repeater_label'] ) ) {
				$this->add_render_attribute(
					'widget_wrapper',
					[
						'data-piotnetforms-repeater-form-id' => $settings['piotnetforms_repeater_form_id'],
						'data-piotnetforms-repeater-id'    => $settings['piotnetforms_repeater_id'],
						'data-piotnetforms-repeater-label' => $settings['piotnetforms_repeater_label'],
						'data-piotnetforms-repeater-limit' => isset( $settings['piotnetforms_repeater_limit'] ) ? $settings['piotnetforms_repeater_limit'] : '',
					]
				);
			}
		}

		if ( ! $editor ) {
			if ( $this->get_type() !== 'field' && $this->get_type() !== 'submit' && $this->get_type() !== 'multi-step-form' ) {
				if ( ! empty( $settings['piotnetforms_conditional_logic_form_enable_new'] ) && ! empty( $settings['piotnetforms_conditional_logic_form_form_id'] ) ) {
					if ( array_key_exists( 'piotnetforms_conditional_logic_form_list_new', $settings ) ) {
						$list_conditional = $settings['piotnetforms_conditional_logic_form_list_new'];
						//if ( ! empty( $list_conditional[0]['piotnetforms_conditional_logic_form_if'] ) && ! empty( $list_conditional[0]['piotnetforms_conditional_logic_form_comparison_operators'] ) ) {
							$this->add_render_attribute(
								'widget_wrapper',
								[
									'data-piotnetforms-conditional-logic' => json_encode( $list_conditional ),
									'data-piotnetforms-conditional-logic-not-field' => '',
									'data-piotnetforms-conditional-logic-not-field-form-id' => $settings['piotnetforms_conditional_logic_form_form_id'],
									'data-piotnetforms-conditional-logic-speed' => $settings['piotnetforms_conditional_logic_form_speed_new'],
									'data-piotnetforms-conditional-logic-easing' => $settings['piotnetforms_conditional_logic_form_easing_new'],
								]
							);
						//}
					}
				}
			}
		}

		$this->before_render();

		echo '<div ' . $this->get_render_attribute_string( 'widget_wrapper' ) . '>';

		if ( $visibility ) {
			$this->render_start( $editor );
		}

		return ob_get_clean();
	}

	public function output_wrapper_end( $widget_id = '', $editor = false ) {
		ob_start();

		$visibility = true;

		if ( ! $editor ) {
			$visibility = $this->widget_visibility();
		}

		if ( $visibility ) {
			$this->render_end( $editor );
			echo '</div>';
		}

		return ob_get_clean();
	}

	public function output( $widget_id = '', $editor = false ) {
		ob_start();

		$settings = $this->settings;

		$widget_information = [
			'type'       => $this->get_type(),
			'class_name' => $this->get_class_name(),
			'title'      => $this->get_title(),
			'icon'       => $this->get_icon(),
		];

		$visibility = true;

		if ( ! $editor ) {
			$visibility = $this->widget_visibility();
		}

		if ( $editor ) {
			$this->add_render_attribute( 'wrapper', 'data-piotnet-editor-widgets-item-root', '' );

			$this->add_render_attribute( 'widget_wrapper_editor', 'class', 'piotnet-widget' );
			$this->add_render_attribute( 'widget_wrapper_editor', 'data-piotnet-editor-widgets-item', json_encode( $widget_information ) );
			$this->add_render_attribute( 'widget_wrapper_editor', 'data-piotnet-editor-widgets-item-id', $widget_id );
			$this->add_render_attribute( 'widget_wrapper_editor', 'draggable', 'true' );

			echo '<div ' . $this->get_render_attribute_string( 'widget_wrapper_editor' ) . '>';

			?>
				<div class="piotnet-widget__controls" data-piotnet-controls>
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--edit" title="Edit" data-piotnet-control-edit>
						<i class="fas fa-th"></i>
					</div>
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--duplicate" title="Duplicate" data-piotnet-control-duplicate>
						<i class="far fa-clone"></i>
					</div>
					<div class="piotnet-widget__controls-item piotnet-widget__controls-item--remove" title="Delete" data-piotnet-control-remove>
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="piotnet-widget__container" data-piotnet-container>
			<?php
		}

		$this->add_render_attribute( 'wrapper', 'class', $widget_id );

		if ( ! empty( $settings['advanced_custom_id'] ) ) {
			$this->add_render_attribute( 'wrapper', 'id', $settings['advanced_custom_id'] );
		}

		if ( ! empty( $settings['advanced_custom_classes'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', $settings['advanced_custom_classes'] );
		}

		if ( ! empty( $settings['piotnetforms_repeater_enable_trigger'] ) && ! empty( $settings['piotnetforms_repeater_form_id_trigger'] ) && ! empty( $settings['piotnetforms_repeater_id_trigger'] ) && ! empty( $settings['piotnetforms_repeater_trigger_action'] ) ) {
			$this->add_render_attribute(
				'wrapper',
				[
					'data-piotnetforms-repeater-form-id-trigger' => $settings['piotnetforms_repeater_form_id_trigger'],
					'data-piotnetforms-repeater-id-trigger' => $settings['piotnetforms_repeater_id_trigger'],
					'data-piotnetforms-repeater-trigger-action' => $settings['piotnetforms_repeater_trigger_action'],
				]
			);
		}

		if ( $this->get_type() !== 'field' && $this->get_type() !== 'submit' && $this->get_type() !== 'multi-step-form' ) {
			if ( ! empty( $settings['piotnetforms_conditional_logic_form_enable_new'] ) && ! empty( $settings['piotnetforms_conditional_logic_form_form_id'] ) ) {
				if ( array_key_exists( 'piotnetforms_conditional_logic_form_list_new', $settings ) ) {
					$list_conditional = $settings['piotnetforms_conditional_logic_form_list_new'];
					//if ( ! empty( $list_conditional[0]['piotnetforms_conditional_logic_form_if'] ) && ! empty( $list_conditional[0]['piotnetforms_conditional_logic_form_comparison_operators'] ) ) {
						$this->add_render_attribute(
							'wrapper',
							[
								'data-piotnetforms-conditional-logic' => json_encode( $list_conditional ),
								'data-piotnetforms-conditional-logic-not-field' => '',
								'data-piotnetforms-conditional-logic-not-field-form-id' => $settings['piotnetforms_conditional_logic_form_form_id'],
								'data-piotnetforms-conditional-logic-speed' => $settings['piotnetforms_conditional_logic_form_speed_new'],
								'data-piotnetforms-conditional-logic-easing' => $settings['piotnetforms_conditional_logic_form_easing_new'],
							]
						);
					//}
				}
			}
		}

		$this->before_render();

		if ( $visibility ) {
			$this->render( $editor );
		}

		if ( $editor ) {
			echo '</div></div>';
		}

		return ob_get_clean();
	}

	public function before_render() {

	}

	public $render_attributes = [];

	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->render_attributes[ $element ][ $key ] ) ) {
			$this->render_attributes[ $element ][ $key ] = [];
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->render_attributes[ $element ][ $key ] = array_merge( $this->render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	public function remove_render_attribute( $element, $key = null, $values = null ) {
		if ( $key && ! isset( $this->render_attributes[ $element ][ $key ] ) ) {
			return;
		}

		if ( $values ) {
			$values = (array) $values;

			$this->render_attributes[ $element ][ $key ] = array_diff( $this->render_attributes[ $element ][ $key ], $values );

			return;
		}

		if ( $key ) {
			unset( $this->render_attributes[ $element ][ $key ] );

			return;
		}

		if ( isset( $this->render_attributes[ $element ] ) ) {
			unset( $this->render_attributes[ $element ] );
		}
	}

	public static function render_html_attributes( array $attributes ) {
		$rendered_attributes = [];

		foreach ( $attributes as $attribute_key => $attribute_values ) {
			if ( is_array( $attribute_values ) ) {
				$attribute_values = implode( ' ', $attribute_values );
			}

			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
		}

		return implode( ' ', $rendered_attributes );
	}

	public function get_render_attribute_string( $element ) {
		if ( empty( $this->render_attributes[ $element ] ) ) {
			return '';
		}

		return $this->render_html_attributes( $this->render_attributes[ $element ] );
	}

}
