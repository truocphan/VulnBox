<?php
	
if( !isset( $id ) || !isset( $value ) ){
	echo 'Input Id/ Value not set';
	return;
}

?>
<div class="xoo-as-upload-container">
	<a class="button-primary xoo-upload-icon">Select</a>
	<input type="hidden" name="<?php echo esc_attr( $id ); ?>" class="xoo-upload-url" value="<?php echo esc_attr( $value ); ?>">
	<a class="button xoo-remove-media">Remove</a>
	<span class="xoo-upload-title"></span>
</div>