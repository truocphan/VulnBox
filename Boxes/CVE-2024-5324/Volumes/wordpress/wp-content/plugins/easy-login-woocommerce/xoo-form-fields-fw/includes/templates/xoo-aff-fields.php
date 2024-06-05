<?php

$fields = xoo_aff()->get_fields_data();
if( empty( $fields ) ) return;

?>

<div class="xoo-aff-fields">
	<?php 

	foreach ( $fields as $field_id => $field_data ){

		$active = $html = $type = $required = $label = $class = $placeholder = $label_text = $icon = $minlength = $maxlength = $default = $cols = $common_atts = null;
		$settings = $field_data['settings'];

		extract( $settings );

		if( !$active ) continue;

		$type 			= esc_attr( $field_data[ 'type' ] );
		$required 		= isset( $required ) && $required === "yes" ? 'required' : '';
		$label 			= isset( $show_label ) && trim( $show_label );
		$class 			= isset( $class ) ? esc_attr( $class ) : '';
		$placeholder 	= isset( $placeholder ) ? esc_attr( $placeholder ): '';
		$label_text 	= isset( $label_text ) ? esc_attr( $label_text ): '';
		$icon 			= isset( $icon ) ? esc_attr( $icon ): '';
		$minlength 		= isset( $minchar ) ? ' minlength="'.esc_attr( $minchar ).'"': null;
		$maxlength 		= isset( $maxchar ) ? ' maxlength="'.esc_attr( $maxchar ).'"': null;
		$default 		= isset( $default ) ? esc_attr( $default ): '';
		$cols 			= isset( $cols ) ? esc_attr( $cols ) : '';
		
		$class 			= $type === 'date' ? $class.' xoo-aff-datepicker' : $class;

		$common_atts 	= "id=\"{$field_id}\" name=\"{$field_id}\" class=\"{$class}\" placeholder=\"{$placeholder}\" {$required}";

		$html .= '<div class="xoo-aff-group '.$cols.' xoo-aff-'.$type.'">';

		if( $label && $label_text ){
			$html .= '<label for='.$field_id.' class="xoo-aff-label">'.$label_text.'</label>';
		}
		$html .= '<div class="xoo-aff-input-group">';

		if( $icon ){
			$html .= '<span class="xoo-aff-input-icon '.$icon.'"></span>';
		}

		switch ( $type ) {
			case 'text':
				$html .= '<input type="text" value="'.$default.'" '.$common_atts. $minlength. $maxlength.'>';
				break;

			case 'email':
				$html .= '<input type="email" value="'.$default.'" '.$common_atts.'>';
				break;
			
			case 'number':
				$html .= '<input type="text" pattern="\d*" value="'.$default.'" '.$common_atts. $minlength. $maxlength.'>';
				break;

			case 'password':
				$html .= '<input type="password" '.$common_atts.'>';
				break;

			case 'date':
				$html .= '<input type="text" name='.$field_id.' class='.$class.' placeholder='.$placeholder.' '.$required.' value="'.$default.'" autocomplete="off">';
				break;


			case 'checkbox_single':
				if( empty( $checkbox_single ) ) break;

				$checkbox = $checkbox_single;

				if( !isset( $checkbox['value'] ) || !$checkbox['value'] ) break;
				$html .= '<label>';
				$html .= '<input type="checkbox" id="'.$field_id.'" name="'.$field_id.'" class="'.$class.'" value="'.$checkbox['value'].'" '.$checkbox['checked'].'>'.$checkbox['label'];
				$html .= '</label>';
				
				break;


			case 'checkbox_list':
				if( empty( $checkbox_list ) ) break;

				$html .= '<div class="xoo-aff-options-list">';
				foreach ( $checkbox_list as $checkbox ) {
					if( !isset( $checkbox['value'] ) || !$checkbox['value'] ) continue;
					$html .= '<label>';
					$html .= '<input type="checkbox" name="'.$field_id.'[]" class="'.$class.'" value="'.$checkbox['value'].'" '.$checkbox['checked'].'>'.$checkbox['label'];
					$html .= '</label>';
				}
				$html .= '</div>';

				break;

			case 'radio':
				if( empty( $radio ) ) break;

				$html .= '<div class="xoo-aff-options-list">';
				foreach ( $radio as $radio_option ) {
					if( !isset( $radio_option['value'] ) || !$radio_option['value'] ) continue;
					$html .= '<label>';
					$html .= '<input type="radio" name="'.$field_id.'" class="'.$class.'" value="'.$radio_option['value'].'" '.$radio_option['checked'].'>'.$radio_option['label'];
					$html .= '</label>';
				}
				$html .= '</div>';

				break;

			case 'select_list':
				if( empty( $select_list ) ) break;

				$html .= '<select class="xoo-aff-options-list" '.$common_atts.'>';
				if( $placeholder ){
					$html .= '<option value="" disabled selected hidden>'.$placeholder.'</option>';
				}
				foreach ( $select_list as $select_option ) {
					if( !isset( $select_option['value'] ) || !$select_option['value'] ) continue;
					$selected =  !$placeholder && $select_option['checked'] ? 'selected' : '';
					$html .= '<option value="'.$select_option['value'].'" '.$selected.'>'.$select_option['label'].'</option>';
				}
				$html .= '</select>';

				break;
		}

		$html .= '</div>';
		$html .= '</div>';
		
		echo apply_filters( 'xoo_aff_field_html', $html, $field_id, $field_data );

	}

	?>


</div>
