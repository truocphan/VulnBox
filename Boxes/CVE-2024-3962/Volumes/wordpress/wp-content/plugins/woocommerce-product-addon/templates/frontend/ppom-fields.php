<?php
/**
 * PPOM Main HTML Template
 *
 * Rendering all fields on product page
 *
 * @version 1.0
 **/

/*
**========== Block direct access ===========
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if duplicate ppom fields render
if ( ! $form_obj::$ppom->has_unique_datanames() ) {
	$duplicate_found = apply_filters( 'ppom_duplicate_datanames_text', __( 'Some of your fields has duplicated datanames, please fix it', 'woocommerce-product-addon' ) );

	echo '<div class="error">' . esc_html( $duplicate_found ) . '</div>';

	return '';
}

// ppom meta ids
$ppom_wrapper_id = is_array( $form_obj::$ppom->meta_id ) ? implode( '-', $form_obj::$ppom->meta_id ) : $form_obj::$ppom->meta_id;

if ( isset( $form_obj::$ppom->meta_id ) ) {
    $ppom_groups = is_array($form_obj::$ppom->meta_id) ? $form_obj::$ppom->meta_id : array($form_obj::$ppom->meta_id);
} else {
    $ppom_groups = array();
}

?>

<div id="ppom-box-<?php echo esc_attr( $ppom_wrapper_id ); ?>" class="ppom-wrapper">


	<!-- Display price table before fields -->
	<?php
	if ( ppom_get_price_table_location() === 'before' ) {
		echo $form_obj->render_price_table_html();
	}
	?>

	<!-- Render hidden inputs -->
	<?php $form_obj->form_contents(); ?>
	<?php
	foreach ( $ppom_groups as $meta_id ) :
		$ppom            = new PPOM_Meta();
		$ppom_settings   = $ppom->get_settings_by_id( $meta_id );
		$form_obj::$ppom->ppom_settings = $ppom_settings;
		?>
		<div class="<?php echo esc_attr( $form_obj->wrapper_inner_classes() ); ?>">

			<?php
			/**
			 * Hook before ppom fields.
			 */
			do_action( 'ppom_before_ppom_fields', $form_obj );
			?>

			<?php $form_obj->ppom_fields_render( $meta_id ); ?>

			<?php
			/**
			 * Hook after ppom fields.
			 */
			do_action( 'ppom_after_ppom_fields', $form_obj );
			?>

		</div>
	<?php endforeach; ?> <!-- end form-row -->

	<!-- Display price table after fields -->
	<?php
	if ( ppom_get_price_table_location() === 'after' ) {
		echo $form_obj->render_price_table_html();
	}
	?>


	<div id="ppom-error-container" class="woocommerce-notices-wrapper"></div>

	<div style="clear:both"></div>

</div>  <!-- end ppom-wrapper -->
