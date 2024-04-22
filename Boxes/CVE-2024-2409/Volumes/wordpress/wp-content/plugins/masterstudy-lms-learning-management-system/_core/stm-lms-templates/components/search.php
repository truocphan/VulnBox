<?php
/**
 * Select component
 *
 * @var string  $search_name     - search input attribute `name`.
 * @var string  $placeholder     - search component placeholder.
 * @var boolean $show_clear_icon - search component clear icon.
 * @var boolean $dark_mode       - if $dark_mode is true then add class
 * `masterstudy-search_dark-mode` to class `masterstudy-search`
 *
 * @package masterstudy
 */

$search_name   = $search_name ?? 's';
$placeholder   = $placeholder ?? __( 'Search', 'masterstudy-lms-learning-management-system' );
$search_class  = ( $dark_mode ?? false ) ? ' masterstudy-search_dark-mode' : '';
$search_class .= ( $show_clear_icon ?? false ) ? ' masterstudy-search_inuse' : '';

wp_enqueue_style( 'masterstudy-search' );
wp_enqueue_script( 'masterstudy-search' );
?>
<div class="masterstudy-search<?php echo esc_attr( $search_class ); ?>">
	<input class="masterstudy-search__input" name="<?php echo esc_attr( $search_name ); ?>" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
	<label class="masterstudy-search__label">
		<span class="masterstudy-search__clear-icon"></span>
		<span class="masterstudy-search__icon"></span>
	</label>
</div>
