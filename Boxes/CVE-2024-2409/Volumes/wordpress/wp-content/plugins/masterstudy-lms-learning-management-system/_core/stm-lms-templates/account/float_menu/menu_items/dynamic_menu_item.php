<?php
/**
 * @var $lms_template
 * @var $menu_url
 * @var $menu_title
 * @var $menu_icon
 * @var $is_active
 * @var $font_pack
 * @var $badge_count
 */
$object_id            = get_queried_object_id();
$lms_template_current = get_query_var( 'lms_template' );
$active_template      = ( $lms_template_current === $lms_template );

if ( empty( $lms_template_current ) && ! empty( $is_active ) ) {
	$active_template = true === $is_active || intval( $is_active ) === $object_id;
}

$active = ( $active_template ) ? 'float_menu_item_active' : '';
if ( ! isset( $font_pack ) ) {
	$font_pack = 'fa';
}
if ( empty( $badge_count ) ) {
	$badge_count = 0;
}
?>

<a href="<?php echo esc_url( $menu_url ); ?>" class="float_menu_item float_menu_item__inline __icon <?php echo esc_attr( $active ); ?>">
	<span class="float_menu_item__title heading_font">
		<?php echo wp_kses_post( $menu_title ); ?>
	</span>
	<i class="<?php echo esc_attr( "{$font_pack} {$menu_icon}" ); ?> float_menu_item__icon"></i>
	<?php if ( ! empty( $badge_count ) ) : ?>
		<abbr><?php echo intval( $badge_count ); ?></abbr>
	<?php endif; ?>
</a>
