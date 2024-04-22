<?php

/**
 * @var int $max_visible_tabs
 * @var int $tabs_quantity
 * @var boolean $vertical
 * @var boolean $dark_mode
 *
 * masterstudy-tabs_dark-mode - for dark mode
 * masterstudy-tabs-pagination_vertical - for vertical view
 */

wp_enqueue_style( 'masterstudy-tabs-pagination' );
wp_enqueue_script( 'masterstudy-tabs-pagination' );
wp_localize_script(
	'masterstudy-tabs-pagination',
	'tabs_data',
	array(
		'max_visible_tabs' => $max_visible_tabs,
		'tabs_quantity'    => $tabs_quantity,
		'item_width'       => 41,
		'vertical'         => $vertical,
	)
);
?>

<div class="masterstudy-tabs-pagination <?php echo esc_attr( $dark_mode ? 'masterstudy-tabs-pagination_dark-mode' : '' ); ?> <?php echo esc_attr( $vertical ? 'masterstudy-tabs-pagination_vertical' : '' ); ?>">
	<div class="masterstudy-tabs-pagination__button-prev-wrapper">
		<span class="masterstudy-tabs-pagination__button-prev"></span>
	</div>
	<div class="masterstudy-tabs-pagination__wrapper">
		<ul class="masterstudy-tabs-pagination__list">
			<?php foreach ( range( 1, $tabs_quantity ) as $index ) { ?>
				<li class="masterstudy-tabs-pagination__item">
					<span class="masterstudy-tabs-pagination__item-block" data-id="<?php echo esc_attr( $index ); ?>">
						<?php echo esc_html( $index ); ?>
					</span>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php if ( $tabs_quantity > $max_visible_tabs ) { ?>
		<div class="masterstudy-tabs-pagination__button-next-wrapper">
			<span class="masterstudy-tabs-pagination__button-next"></span>
		</div>
	<?php } ?>
</div>
