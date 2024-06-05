<?php if( !isset($field_types) ) return; ?>

<div class="xoo-aff-field-selector">
	
	<?php do_action( 'xoo_aff_field_selector', $aff ); ?>

	<span>Select one of the field below.</span>
	<ul class="xoo-aff-select-fields-type">

		<?php foreach ( $field_types as $type_id => $type_data ): ?>
			<?php if( $type_data['is_selectable'] === "yes" ): ?>
				<li data-field="<?php echo esc_attr( $type_id ); ?>"><?php echo esc_html( $type_data['title'] ); ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>

</div>