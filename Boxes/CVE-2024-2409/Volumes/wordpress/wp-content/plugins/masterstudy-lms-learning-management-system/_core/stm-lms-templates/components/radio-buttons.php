<?php
/**
 * Radio buttons component
 *
 * @var string $name       - attribute name for group radio elements
 * @var boolean $dark_mode - if $dark_mode is true then add class
 * `masterstudy-radio-buttons_dark-mode` to class `masterstudy-radio-buttons`
 * @var boolean $vertical  - if $vertical is true then add class
 * `masterstudy-radio-buttons_vertical` to class `masterstudy-radio-buttons`
 * @var array   $items     - radio buttons arguments {
 *      @var string  $value     - value of radio element
 *      @var string  $label     - label title|text of radio element
 *      @var boolean $disabled  - if $disabled is true then adds disabled attribute to radio element
 *      @var string  $hidden    - if $hidden is true then radio element is hidden, otherwise ratio type element
 * }
 *
 * @package masterstudy
 */

$container_class  = ( $dark_mode ?? false ) ? ' masterstudy-radio-buttons_dark-mode' : '';
$container_class .= ( $vertical ?? false ) ? ' masterstudy-radio-buttons_vertical' : '';
$items            = ! empty( $items ) && is_array( $items ) ? $items : array();
$radio_name       = ! empty( $name ) ? $name . '-' : '';

wp_enqueue_style( 'masterstudy-radio-buttons' );
wp_enqueue_script( 'masterstudy-radio-buttons' );
?>
<div class="masterstudy-radio-buttons<?php echo esc_attr( $container_class ); ?>">
	<?php foreach ( $items as $i => $item ) : ?>
		<?php
			$item_class  = ! empty( $item['checked'] ) ? 'masterstudy-radio__label_checked' : '';
			$item_class .= ! empty( $item['style'] ) ? ' masterstudy-radio_' . $item['style'] : ' masterstudy-radio_success';
		?>
	<label for="<?php echo esc_attr( $radio_name . $i ); ?>" class="<?php echo esc_attr( $item_class ); ?>">
		<input id="<?php echo esc_attr( $radio_name . $i ); ?>" type="<?php echo esc_attr( ! empty( $item['hidden'] ) ? 'hidden' : 'radio' ); ?>" name="<?php echo esc_attr( $name ?? '' ); ?>" value="<?php echo esc_attr( $item['value'] ?? '' ); ?>" <?php echo esc_attr( ! empty( $item['disabled'] ) ? 'disabled' : '' ); ?>  <?php echo esc_attr( ! empty( $item['checked'] ) ? 'checked' : '' ); ?>>
		<?php echo esc_html( $item['label'] ?? '' ); ?>
	</label>
	<?php endforeach; ?>
</div>
