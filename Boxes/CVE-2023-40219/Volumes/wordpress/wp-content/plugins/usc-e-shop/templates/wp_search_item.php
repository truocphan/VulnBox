<?php
/**
 * Search item page template
 *
 * @package Welcart
 */

usces_the_item();

$html = '<div class="loopimg">
	<a href="' . get_permalink( $post->ID ) . '">' . usces_the_itemImage( 0, 300, 300, $post, 'return' ) . '</a>
	</div>
	<div class="loopexp">
		<div class="field">' . $content . '</div>
	</div>';
$html = apply_filters( 'usces_filter_item_list_loopimg', $html, $content );
