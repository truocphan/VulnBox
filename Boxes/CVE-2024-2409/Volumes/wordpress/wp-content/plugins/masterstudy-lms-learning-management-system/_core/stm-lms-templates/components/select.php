<?php
/**
 * Select component
 *
 * @var string  $select_id    - select ID.
 * @var string  $select_name  - select attribute `name`.
 * @var string  $select_width - select component width.
 * @var string  $placeholder  - select component placeholder.
 * @var mixed   $default      - select component default value.
 * @var array   $options      - select component options items
 * (option_value = array_key, option_content = array_value).
 * @var boolean $dark_mode    - if $dark_mode is true then add class
 * `masterstudy-select_dark-mode` to class `masterstudy-select`
 *
 * @package masterstudy
 */

$select_name  = $select_name ?? '';
$select_id    = $select_id ?? $select_name;
$placeholder  = $placeholder ?? __( 'Select an item', 'masterstudy-lms-learning-management-system' );
$options      = is_array( $options ) ? $options : array();
$dark_mode    = $dark_mode ?? false;
$select_width = ! empty( $select_width ) ? 'style=min-width:' . $select_width : '';
$default      = $default ?? '';
$queryable    = ( $is_queryable ?? false ) ? 'true' : 'false';

wp_enqueue_style( 'masterstudy-select', STM_LMS_URL . '/assets/css/components/select.css', null, MS_LMS_VERSION );
wp_enqueue_script( 'masterstudy-select', STM_LMS_URL . 'assets/js/components/select.js', array(), MS_LMS_VERSION, true );
?>
<div class="masterstudy-select<?php echo esc_attr( $dark_mode ? ' masterstudy-select_dark-mode' : '' ); ?>" <?php echo esc_attr( $select_width ); ?> data-queryable="<?php echo esc_attr( $queryable ); ?>" data-id="<?php echo esc_attr( $select_id ); ?>">
	<input id="<?php echo esc_attr( $select_id ); ?>" name="<?php echo esc_attr( $select_name ); ?>" class="masterstudy-select__input" type="hidden" value="<?php echo esc_attr( $default ); ?>">
	<div class="masterstudy-select__wrapper">
		<div class="masterstudy-select__placeholder" data-initial="<?php echo esc_attr( $default ); ?>" data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
			<?php echo esc_html( $placeholder ); ?>
		</div>
		<span class="masterstudy-select__clear">
			<span class="masterstudy-select__clear-icon"></span>
		</span>
		<span class="masterstudy-select__caret"></span>
	</div>
	<div class="masterstudy-select__dropdown">
		<ul class="masterstudy-select__options">
			<?php foreach ( $options as $value => $content ) : ?>
				<li class="masterstudy-select__option" data-value="<?php echo esc_attr( $value ); ?>">
					<?php echo esc_html( $content ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
