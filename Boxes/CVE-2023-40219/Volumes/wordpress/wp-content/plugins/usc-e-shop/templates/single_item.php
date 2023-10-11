<?php
/**
 * Single item page template
 *
 * @package Welcart
 */

usces_the_item();

$html  = '<div id="itempage">';
$html .= '<form action="' . esc_url( USCES_CART_URL ) . '" method="post">';

$html      .= '<div class="itemimg">';
$html      .= '<a href="' . usces_the_itemImageURL( 0, 'return' ) . '"';
$html      .= apply_filters( 'usces_itemimg_anchor_rel', '' );
$html      .= '>';
$item_image = usces_the_itemImage( 0, 200, 250, $post, 'return' );
$html      .= apply_filters( 'usces_filter_the_itemImage', $item_image, $post );
$html      .= '</a>';
$html      .= '</div>';

if ( 1 === usces_sku_num() ) {
	usces_have_skus();

	$html .= '<h3>' . esc_html( usces_the_itemName( 'return' ) ) . '&nbsp; (' . esc_html( usces_the_itemCode( 'return' ) ) . ') </h3>';
	$html .= '<div class="exp clearfix">';
	$html .= '<div class="field">';
	if ( 0 < usces_the_itemCprice( 'return' ) ) {
		$usces_listprice = esc_html__( 'List price', 'usces' ) . usces_guid_tax( 'return' );
		$html           .= '<div class="field_name">' . apply_filters( 'usces_filter_listprice_label', $usces_listprice, esc_html__( 'List price', 'usces' ), usces_guid_tax( 'return' ) ) . '</div>';
		$html           .= '<div class="field_cprice">' . usces_the_itemCpriceCr( 'return' ) . '</div>';
	}
	$usces_sellingprice = esc_html__( 'selling price', 'usces' ) . usces_guid_tax( 'return' );
	$html              .= '<div class="field_name">' . apply_filters( 'usces_filter_sellingprice_label', $usces_sellingprice, esc_html__( 'selling price', 'usces' ), usces_guid_tax( 'return' ) ) . '</div>';
	$html              .= '<div class="field_price">' . usces_the_itemPriceCr( 'return' ) . '</div>';
	$html              .= usces_crform_the_itemPriceCr_taxincluded( true, '', '', '', true, false, true, 'return' );
	$html              .= '</div>';
	$singlestock        = '<div class="field">' . esc_html__( 'stock status', 'usces' ) . ' : ' . esc_html( usces_get_itemZaiko( 'name' ) ) . '</div>';
	$html              .= apply_filters( 'single_item_stock_field', $singlestock );
	$item_custom        = usces_get_item_custom( $post->ID, 'list', 'return' );
	if ( $item_custom ) {
		$html .= '<div class="field">';
		$html .= $item_custom;
		$html .= '</div>';
	}

	$html .= $content;
	$html .= '</div><!-- end of exp -->';
	$html .= usces_the_itemGpExp( 'return' );
	$html .= '<div class="skuform" align="right">';
	if ( usces_is_options() ) {
		$html .= '<table class="item_option"><caption>' . apply_filters( 'usces_filter_single_item_options_caption', esc_html__( 'Please appoint an option.', 'usces' ), $post ) . '</caption>';
		while ( usces_have_options() ) {
			$opttr = '<tr><th>' . esc_html( usces_getItemOptName() ) . '</th><td>' . usces_the_itemOption( usces_getItemOptName(), '', 'return' ) . '</td></tr>';
			$html .= apply_filters( 'usces_filter_singleitem_option', $opttr, usces_getItemOptName(), null );
		}
		$html .= '</table>';
	}
	if ( ! usces_have_zaiko() ) {
		$html .= '<div class="zaiko_status">' . apply_filters( 'usces_filters_single_sku_zaiko_message', esc_html( usces_get_itemZaiko( 'name' ) ) ) . '</div>';
	} else {
		$html .= '<div style="margin-top:10px">' . esc_html__( 'Quantity', 'usces' ) . usces_the_itemQuant( 'return' ) . esc_html( usces_the_itemSkuUnit( 'return' ) ) . usces_the_itemSkuButton( esc_html__( 'Add to Shopping Cart', 'usces' ), 0, 'return' ) . '</div>';
		$html .= '<div class="error_message">' . usces_singleitem_error_message( $post->ID, usces_the_itemSku( 'return' ), 'return' ) . '</div>';
	}

	$html .= '</div><!-- end of skuform -->';
	$html .= apply_filters( 'single_item_single_sku_after_field', null );

} elseif ( 1 < usces_sku_num() ) {
	usces_have_skus();

	$html       .= '<h3>' . usces_the_itemName( 'return' ) . '&nbsp; (' . usces_the_itemCode( 'return' ) . ') </h3>';
	$html       .= '<div class="exp clearfix">';
	$html       .= $content;
	$item_custom = usces_get_item_custom( $post->ID, 'list', 'return' );
	if ( $item_custom ) {
		$html .= '<div class="field">';
		$html .= $item_custom;
		$html .= '</div>';
	}
	$html .= '</div>';

	$html .= '<div class="skuform">';
	$html .= '<table class="skumulti">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th rowspan="2" class="thborder">' . esc_html__( 'order number', 'usces' ) . '</th>';
	$html .= '<th colspan="2">' . esc_html__( 'Title', 'usces' ) . '</th>';
	if ( 0 < usces_the_itemCprice( 'return' ) ) {
		$usces_bothprice = '(' . esc_html__( 'List price', 'usces' ) . ')' . esc_html__( 'selling price', 'usces' ) . usces_guid_tax( 'return' );
		$html           .= '<th colspan="2">' . apply_filters( 'usces_filter_bothprice_label', $usces_bothprice, esc_html__( 'List price', 'usces' ), esc_html__( 'selling price', 'usces' ), usces_guid_tax( 'return' ) ) . '</th>';
	} else {
		$usces_sellingprice = esc_html__( 'selling price', 'usces' ) . usces_guid_tax( 'return' );
		$html              .= '<th colspan="2">' . apply_filters( 'usces_filter_sellingprice_label', $usces_sellingprice, esc_html__( 'selling price', 'usces' ), usces_guid_tax( 'return' ) ) . '</th>';
	}
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<th class="thborder">' . esc_html__( 'stock status', 'usces' ) . '</th>';
	$html .= '<th class="thborder">' . esc_html__( 'Quantity', 'usces' ) . '</th>';
	$html .= '<th class="thborder">' . esc_html__( 'unit', 'usces' ) . '</th>';
	$html .= '<th class="thborder">&nbsp;</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	do {
		$html .= '<tr>';
		$html .= '<td rowspan="2">' . esc_html( usces_the_itemSku( 'return' ) ) . '</td>';
		$html .= '<td colspan="2" class="skudisp subborder">' . apply_filters( 'usces_filter_singleitem_skudisp', esc_html( usces_the_itemSkuDisp( 'return' ) ) );
		if ( usces_is_options() ) {
			$html .= '<table class="item_option"><caption>' . apply_filters( 'usces_filter_single_item_options_caption', esc_html__( 'Please appoint an option.', 'usces' ), $post ) . '</caption>';
			while ( usces_have_options() ) {
				$opttr = '<tr><th>' . esc_html( usces_getItemOptName() ) . '</th><td>' . usces_the_itemOption( usces_getItemOptName(), '', 'return' ) . '</td></tr>';
				$html .= apply_filters( 'usces_filter_singleitem_option', $opttr, usces_getItemOptName(), null );
			}
			$html .= '</table>';
		}
		$html .= '</td>';
		$html .= '<td colspan="2" class="subborder price">';
		if ( 0 < usces_the_itemCprice( 'return' ) ) {
			$html .= '<span class="cprice">(' . usces_the_itemCpriceCr( 'return' ) . ')</span>';
		}
		$html .= '<span class="price">' . usces_the_itemPriceCr( 'return' ) . '</span>';
		$html .= usces_crform_the_itemPriceCr_taxincluded( true, '', '', '', true, false, true, 'return' );
		$html .= '<br />' . usces_the_itemGpExp( 'return' ) . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="zaiko">' . usces_get_itemZaiko( 'name' ) . '</td>';
		$html .= '<td class="quant">' . usces_the_itemQuant( 'return' ) . '</td>';
		$html .= '<td class="unit">' . usces_the_itemSkuUnit( 'return' ) . '</td>';
		if ( ! usces_have_zaiko() ) {
			$html .= '<td class="button">' . apply_filters( 'usces_filters_single_sku_zaiko_message', esc_html( usces_get_itemZaiko( 'name' ) ) ) . '</td>';
		} else {
			$html .= '<td class="button">' . usces_the_itemSkuButton( esc_html__( 'Add to Shopping Cart', 'usces' ), 0, 'return' ) . '</td>';
		}
		$html .= '</tr>';
		$html .= '<tr><td colspan="5" class="error_message">' . usces_singleitem_error_message( $post->ID, usces_the_itemSku( 'return' ), 'return' ) . '</td></tr>';

	} while ( usces_have_skus() );
	$html .= '</tbody>';
	$html .= '</table>';
	$html .= '</div><!-- end of skuform -->';
	$html .= apply_filters( 'single_item_multi_sku_after_field', null );
}

$html   .= '<div class="itemsubimg">';
$imageid = usces_get_itemSubImageNums();
foreach ( $imageid as $id ) {
	$html      .= '<a href="' . usces_the_itemImageURL( $id, 'return' ) . '"';
	$html      .= apply_filters( 'usces_itemimg_anchor_rel', '' );
	$html      .= '>';
	$item_image = usces_the_itemImage( $id, 137, 200, $post, 'return' );
	$html      .= apply_filters( 'usces_filter_the_SubImage', $item_image, $post, $id );
	$html      .= '</a>';
}
$html .= '</div><!-- end of itemsubimg -->';

if ( usces_get_assistance_id_list( $post->ID ) ) {
	$post_id = $post->ID;

	$html .= '<div class="assistance_item">';

	$r = new WP_Query( array( 'post__in' => usces_get_assistance_ids( $post_id ), 'ignore_sticky_posts' => 1 ) );
	if ( $r->have_posts() ) {
		add_filter( 'excerpt_length', 'welcart_assistance_excerpt_length' );
		add_filter( 'excerpt_mblength', 'welcart_assistance_excerpt_mblength' );
		$width  = apply_filters( 'usces_filter_assistance_item_width', 100 );
		$height = apply_filters( 'usces_filter_assistance_item_height', 100 );

		$assistance_item_title = '<h3>' . usces_the_itemCode( 'return' ) . esc_html__( 'An article concerned', 'usces' ) . '</h3>';
		$html                 .= apply_filters( 'usces_assistance_item_title', $assistance_item_title );
		$html                 .= '<ul class="clearfix">';
		while ( $r->have_posts() ) {
			$r->the_post();
			usces_remove_filter();
			usces_the_item();
			$html .= '<li><div class="listbox clearfix">';
			$html .= '<div class="slit"><a href="' . get_permalink() . '" rel="bookmark" title="' . esc_attr( get_the_title() ) . '">' . usces_the_itemImage( 0, $width, $height, $post, 'return' ) . '</a></div>';
			$html .= '<div class="detail">';
			$html .= '<h4>' . usces_the_itemName( 'return' ) . '</h4>';
			$html .= get_the_excerpt();
			$html .= '<p>';
			if ( usces_is_skus() ) {
				$html .= usces_crform( usces_the_firstPrice( 'return' ), true, false, 'return' );
			}
			$html .= '<br />';
			$html .= '&raquo; <a href="' . get_permalink() . '" rel="bookmark" title="' . esc_attr( get_the_title() ) . '">' . esc_html__( 'see the details', 'usces' ) . '</a></p>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		wp_reset_postdata();
		usces_reset_filter();
		remove_filter( 'excerpt_length', 'welcart_assistance_excerpt_length' );
		remove_filter( 'excerpt_mblength', 'welcart_assistance_excerpt_mblength' );
	}
	$html .= '</div><!-- end of assistance_item -->';
}

$html  = apply_filters( 'usces_filter_single_item_inform', $html );
$html .= '</form>';
$html .= apply_filters( 'usces_filter_single_item_outform', null );

$html .= '</div><!-- end of itemspage -->';
$html  = apply_filters( 'usces_filter_single_item', $html, $post, $content );
