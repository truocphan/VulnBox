<?php
function sc_direct_intoCart($atts) {
	global $usces;
	extract(shortcode_atts(array(
		'item' => '',
		'sku' => '',
		'value' => NULL,
		'options' => NULL,
	), $atts));

	if( WCUtils::is_blank($item) || WCUtils::is_blank($sku) ) return '';
	
	$post_id = $usces->get_ID_byItemName($item);
	
	return usces_direct_intoCart($post_id, $sku, true, $value, $options, 'return');
}
?>