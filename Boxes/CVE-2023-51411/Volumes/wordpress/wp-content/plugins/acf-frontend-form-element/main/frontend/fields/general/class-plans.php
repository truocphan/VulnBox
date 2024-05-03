<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'Frontend_Admin\Field_Types\plans' ) ) :


	class plans extends Field_Base {


		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since   5.0.0
		*
		*  @param   n/a
		*  @return  n/a
		*/

		function initialize() {

			// vars
			$this->name     = 'fea_plans';
			$this->label    = __( 'Plans', 'acf-frontend-form-element' );
			$this->category = 'relational';
			$this->public = false;
			$this->defaults = array(
				'allow_null'    => 0,
				'multiple'      => 0,
				'return_format' => 'object',
				'ui'            => 1,
			);


		}

		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param   $field - an array holding all the field's data
		*
		*  @type    action
		*  @since   3.6
		*  @date    23/01/13
		*/

		function render_field( $field ) {

			// convert
			$field['value'] = acf_get_array( $field['value'] );
			$plans = fea_instance()->plans_handler->get_plans();

			$choices = [];

			?>
			<div class="fea-plans">

			<?php
			foreach( $plans as $plan ){
				$this->render_single_plan( $plan, $field );
			}
			$this->render_single_plan( 'clone', $field );
			?>
			</div>
			<button type="button" class="acf-btn acf-btn-secondary add-plan"><?php esc_html_e( 'Add New Plan', 'acf-frontend-form-element' ); ?></button>

			<?php
		}

		function render_single_plan( $plan, $field ){
			if( 'clone' == $plan ){
				$choice = [
					'label' => '',
					'value' => '',
					'name' => $field['name'],
					'disabled' => 'disabled'
				];
			}else{
				$choice = [
					'label' => $plan['title'],
					'value' => $plan['id'],
					'name' => $field['name']
				];
				if( in_array( intval( $plan['id'] ), $field['value'], true ) ){
					$choice['checked'] = true;
				}
			}
			
			?>
			<div class="fea-single-plan <?php if( 'clone' == $plan ) echo 'acf-hidden clone'; ?>" data-plan="<?php esc_attr_e( $choice['value'] ); ?>">
			<?php
			$label = '';
			if ( isset( $choice['label'] ) ) {
				$label = $choice['label'];
				unset( $choice['label'] );
			}
		
			// Render.
			$checked = isset( $choice['checked'] );
			echo '<label' . ( $checked ? ' class="selected"' : '' ) . '><input type="radio" ' . acf_esc_attr( $choice ) . '/> <span class="fea-plan-title">' . acf_esc_html( $label ) . '</span></label>';
			?>
			<a href="#" title="<?php esc_html_e( 'Edit Plan', 'acf-frontend-form-element' ); ?>" class="acf-icon -pencil small edit-plan"></a>
			<a href="#" title="<?php esc_html_e( 'Delete Plan', 'acf-frontend-form-element' ); ?>" class="acf-icon -minus small delete-plan"></a>
			</div>
			<?php
		}

	}

endif; // class_exists check


