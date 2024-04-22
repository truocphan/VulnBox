<?php

/**
 * @var int $max_visible_pages
 * @var int $total_pages
 * @var int $current_page
 * @var boolean $done_indicator
 * @var boolean $dark_mode
 *
 * masterstudy-pagination_dark-mode - for dark mode
 * masterstudy-pagination__item_current - for current page
 */

wp_enqueue_style( 'masterstudy-pagination' );
wp_enqueue_script( 'masterstudy-pagination' );
wp_localize_script(
	'masterstudy-pagination',
	'pages_data',
	array(
		'max_visible_pages' => $max_visible_pages,
		'total_pages'       => $total_pages,
		'current_page'      => $current_page,
		'is_queryable'      => $is_queryable ?? false,
		'item_width'        => 50,
	)
);
?>

<div class="masterstudy-pagination <?php echo esc_attr( $dark_mode ? 'masterstudy-pagination_dark-mode' : '' ); ?>">
	<span class="masterstudy-pagination__button-prev <?php echo esc_attr( 1 === $current_page ? 'masterstudy-pagination__button_disabled' : '' ); ?>"></span>
	<div class="masterstudy-pagination__wrapper">
		<ul class="masterstudy-pagination__list">
			<?php foreach ( range( 1, $total_pages ) as $index ) { ?>
				<li class="masterstudy-pagination__item <?php echo esc_attr( ( $index ) === $current_page ? 'masterstudy-pagination__item_current' : '' ); ?>">
					<span class="masterstudy-pagination__item-block" data-id="<?php echo esc_attr( $index ); ?>">
						<?php if ( $done_indicator ) { ?>
							<span class="masterstudy-pagination__item-indicator"></span>
							<?php
						}
						echo esc_html( $index );
						?>
					</span>
				</li>
			<?php } ?>
		</ul>
	</div>
	<span class="masterstudy-pagination__button-next"></span>
</div>
