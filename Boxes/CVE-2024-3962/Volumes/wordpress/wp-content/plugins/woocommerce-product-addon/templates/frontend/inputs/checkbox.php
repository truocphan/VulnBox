<?php
/**
 * Checkbox Input Template
 *
 * This template can be overridden by copying it to yourtheme/ppom/frontend/inputs/checkbox.php
 *
 * @version 1.0
 **/

/* 
**========== Block direct access =========== 
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fm = new PPOM_InputManager( $field_meta, 'checkbox' );

$options = ppom_convert_options_to_key_val( $fm->options(), $field_meta, $product );
// ppom_pa($options);

$has_discount = array_filter(
	$options,
	function ( $o ) {
		return $o['discount'] > 0;
	} 
);

$has_discount = count( $has_discount ) > 0 ? true : false;

$onetime = $fm->get_meta_value( 'onetime' );
$taxable = $fm->get_meta_value( 'onetime_taxable' );

// If options empty
if ( ! $options ) {

	echo '<div class="ppom-option-notice">';
	echo '<p>' . __( 'Please add some options to render this input.', 'woocommerce-product-addon' ) . '</p>';
	echo '</div>';

	return '';
}

// Defualt Checked Values
$checked_value = array();
if ( is_array( $default_value ) ) {
	$checked_value = array_map(
		function ( $v ) {
			$v = stripcslashes( $v );
			$v = trim( $v );

			return $v;
		},
		$default_value 
	);
}

$wrapper_class = $has_discount ? 'form-check' : 'form-check-inline';

$check_wrapper_class = apply_filters( 'ppom_checkbox_wrapper_class', $wrapper_class );
$product_type        = $product->get_type();

// change checkbox input form name 
// add_filter('ppom_input_name_attr', function($form_name, $meta){
// if( $meta['type'] == 'checkbox' ) {
// $dataname = $meta['data_name'];
// $form_name = "ppom[fields][".esc_attr($dataname)."][]";
// }
// return $form_name;
// }, 11, 2);
?>

<div class="<?php echo esc_attr( $fm->field_inner_wrapper_classes() ); ?>">

	<!-- if title of field exist -->
	<?php if ( $fm->field_label() ) : ?>
		<label class="<?php echo esc_attr( $fm->label_classes() ); ?>"
			   for="<?php echo esc_attr( $fm->data_name() ); ?>"><?php echo $fm->field_label(); ?></label>
	<?php endif ?>


	<?php
	foreach ( $options as $key => $value ) {

		$option_label   = $value['label'];
		$option_price   = $value['price'];
		$discount_price = $value['discount'];
		$tooltip        = $value['tooltip'];
		$raw_label      = $value['raw'];
		$without_tax    = $value['without_tax'];
		$option_id      = $value['option_id'];
		$dom_id         = apply_filters( 'ppom_dom_option_id', $option_id, $field_meta );
		$opt_percent    = isset( $value['percent'] ) ? $value['percent'] : '';

		// if discount price set
		if ( $has_discount ) {
			$price        = $discount_price > 0 ? wc_format_sale_price( $option_price, $discount_price ) : wc_price( $option_price );
			$tooltip      = $tooltip ? ' <span data-ppom-tooltip="ppom_tooltip" class="ppom-tooltip" title="' . esc_attr( $tooltip ) . '"><svg width="13px" height="13px" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z"></path></svg></span>' : '';
			$the_label    = $value['raw'] . $tooltip;
			$option_label = '<span class="ppom-cb-label">' . $the_label . '</span><span class="ppom-cb-price">' . $price . '</span>';
		}

		$option_price = $discount_price > 0 ? $discount_price : $option_price;


		$ppom_has_percent = $opt_percent !== '' ? 'ppom-option-has-percent' : '';
		$option_class     = array(
			"ppom-option-{$option_id}",
			"ppom-{$product_type}-option",
			$ppom_has_percent,
		);

		$option_class = apply_filters( 'ppom_option_classes', implode( ' ', $option_class ), $field_meta );
		$input_class  = $fm->input_classes() . ' ' . $option_class;

		$checked_option = '';
		if ( count( $checked_value ) > 0 && in_array( $key, $checked_value ) && ! empty( $key ) ) {

			$checked_option = checked( $key, $key, false );
		}


		?>
		<div class="<?php echo esc_attr( $check_wrapper_class ); ?>">
			<label class="<?php echo esc_attr( $fm->checkbox_label_classes() ); ?>"
				   for="<?php echo esc_attr( $dom_id ); ?>">

				<input
						type="checkbox"
						name="<?php echo esc_attr( $fm->form_name() ); ?>"
						id="<?php echo esc_attr( $dom_id ); ?>"
						class="<?php echo esc_attr( $input_class ); ?>"
						data-optionid="<?php echo esc_attr( $option_id ); ?>"
						data-price="<?php echo esc_attr( $option_price ); ?>"
						data-percent="<?php echo esc_attr( $opt_percent ); ?>"
						data-label="<?php echo esc_attr( $raw_label ); ?>"
						data-title="<?php echo esc_attr( $fm->title() ); ?>"
						data-onetime="<?php echo esc_attr( $onetime ); ?>"
						data-taxable="<?php echo esc_attr( $taxable ); ?>"
						data-without_tax="<?php echo esc_attr( $without_tax ); ?>"
						data-data_name="<?php echo esc_attr( $fm->data_name() ); ?>"
						value="<?php echo esc_attr( $key ); ?>"
						<?php echo $checked_option; ?>
				>


				<span class="ppom-input-option-label ppom-label-checkbox"><?php echo $option_label; ?></span>
			</label>
		</div>

	<?php } ?>
</div>
