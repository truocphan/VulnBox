<?php

/**
 * @var array $items
 * @var string $style
 * @var int $active_tab_index
 * @var boolean $dark_mode
 *
 * masterstudy-tabs_dark-mode - for dark mode
 * masterstudy-tabs__item_active - for item active state
 * masterstudy-tabs_style-default|nav-sm|nav-md - for tabs style change
 */

wp_enqueue_style( 'masterstudy-tabs' );
?>

<ul class="masterstudy-tabs <?php echo esc_attr( $dark_mode ? 'masterstudy-tabs_dark-mode' : '' ); ?> <?php echo esc_attr( 'masterstudy-tabs_style-' . $style ); ?>">
	<?php foreach ( $items as $index => $item ) { ?>
		<li class="masterstudy-tabs__item <?php echo $active_tab_index === $index ? 'masterstudy-tabs__item_active' : ''; ?>" data-id="<?php echo esc_attr( $item['id'] ); ?>">
			<?php echo esc_html( $item['title'] ); ?>
		</li>
	<?php } ?>
</ul>
