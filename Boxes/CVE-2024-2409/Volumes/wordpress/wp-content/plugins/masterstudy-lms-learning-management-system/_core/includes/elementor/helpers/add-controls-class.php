<?php
use Elementor\Controls_Manager;

trait MsLmsAddControls {

	/* add data slot for courses grid widget */
	public function add_slot_control( $key, $args = array() ) {
		$defaults = array(
			'type'               => Controls_Manager::SELECT,
			'default'            => 'current-students',
			'frontend_available' => true,
			'options'            => array(
				'lectures'         => esc_html__( 'Lectures', 'masterstudy-lms-learning-management-system' ),
				'duration'         => esc_html__( 'Duration', 'masterstudy-lms-learning-management-system' ),
				'views'            => esc_html__( 'Views', 'masterstudy-lms-learning-management-system' ),
				'level'            => esc_html__( 'Level', 'masterstudy-lms-learning-management-system' ),
				'current-students' => esc_html__( 'Members', 'masterstudy-lms-learning-management-system' ),
				'empty'            => esc_html__( 'Empty', 'masterstudy-lms-learning-management-system' ),
			),
			'conditions'         => array(
				'terms' => array(
					array(
						'name'     => 'show_slots',
						'operator' => '===',
						'value'    => 'yes',
					),
				),
			),
		);

		$this->add_control( $key, wp_parse_args( $args, $defaults ) );
	}

	/* add switchers for courses grid widget */
	public function add_switcher_control( $key, $args = array() ) {
		$defaults = array(
			'type'               => Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
			'label_off'          => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
			'return_value'       => 'yes',
			'default'            => 'yes',
			'frontend_available' => true,
		);

		$this->add_control( $key, wp_parse_args( $args, $defaults ) );
	}

	/* add subswitchers for courses grid widget */
	public function add_subswitcher_control( $key, $args = array() ) {
		$defaults = array(
			'type'               => Controls_Manager::SWITCHER,
			'label'              => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
			'label_on'           => esc_html__( 'Yes', 'masterstudy-lms-learning-management-system' ),
			'label_off'          => esc_html__( 'No', 'masterstudy-lms-learning-management-system' ),
			'frontend_available' => false,
		);

		$this->add_control( $key, wp_parse_args( $args, $defaults ) );
	}

	/* add conditions for controls in courses grid widget */
	public function add_visible_conditions( $type ) {
		return array(
			'terms' => array(
				array(
					'name'     => $type,
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		);
	}

	/* add popup slots conditions for courses grid widget */
	public function add_popup_slot_conditions( $args = array() ) {
		$defaults = array(
			array(
				'name'     => 'show_popup',
				'operator' => '===',
				'value'    => 'yes',
			),
			array(
				'name'     => 'popup_show_slots',
				'operator' => '===',
				'value'    => 'yes',
			),
		);
		if ( ! empty( $args ) ) {
			array_push( $defaults, $args );
		}
		return array( 'terms' => $defaults );
	}

	/* add popup visible conditions for courses grid widget */
	public function add_popup_visible_conditions( $args = array(), $card_presets = array() ) {
		$defaults = array(
			'terms' => array(
				array(
					'name'     => 'show_popup',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => $card_presets,
				),
			),
		);
		if ( ! empty( $args ) ) {
			foreach ( $args as $item ) {
				array_push( $defaults['terms'], $item );
			}
		}
		return $defaults;
	}

	/* add card status conditions for controls in courses grid widget */
	public function add_card_status_conditions( $preset, $args = array() ) {
		$conditions = array(
			'terms' => array(
				array(
					'name'     => 'status_presets',
					'operator' => '===',
					'value'    => $preset,
				),
			),
		);
		if ( ! empty( $args ) ) {
			array_push( $conditions['terms'], $args );
		}
		return $conditions;
	}

	/* add widget's type conditions in courses grid widget */
	public function add_widget_type_conditions( $type ) {
		return array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => $type,
				),
			),
		);
	}

	public function get_categories_terms( $type ) {
		$terms = get_terms(
			'stm_lms_course_taxonomy',
			array(
				'orderby'    => 'count',
				'order'      => 'DESC',
				'hide_empty' => true,
			)
		);
		if ( 'all' === $type ) {
			$term_array = array();
			foreach ( $terms as $term ) {
				$term_array[ $term->term_id ] = $term->name;
			}
			return $term_array;
		} elseif ( 'default' === $type ) {
			$default_terms = array_map(
				function( $term ) {
					return array( $term->term_id );
				},
				$terms
			);
			$default_terms = array_merge( ...$default_terms );
			return $default_terms;
		}
	}

	public function get_instructors() {
		$instructors = get_users(
			array(
				'role__in' => array( 'administrator', 'stm_lms_instructor' ),
				'orderby'  => 'display_name',
				'order'    => 'ASC',
			)
		);
		if ( ! empty( $instructors ) ) {
			$instructor_array = array_reduce(
				$instructors,
				function( $result, $instructor ) {
					$result[ $instructor->ID ] = $instructor->display_name;
					return $result;
				},
				array()
			);
			return $instructor_array;
		}
	}

	/* add button visible conditions for slider widget */
	public function add_button_visible_conditions( $button ) {
		return array(
			'terms' => array(
				array(
					'name'     => $button,
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'show_info_block',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		);
	}
}
