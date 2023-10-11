<?php
/* order data class */

class orderDataObject
{
	var $customer;
	var $deliveri;
	var $cart;
	var $condition;
	var $order;
	var $reserve;

	function __construct($order_id) {
		global $usces, $wpdb, $usces_settings;

		$order_table = $wpdb->prefix . "usces_order";
		$meta_table = $wpdb->prefix . "usces_order_meta";
		$query = $wpdb->prepare("SELECT o.*, om.meta_value AS `order_country` 
			FROM {$order_table} AS `o` 
			LEFT JOIN {$meta_table} AS `om` ON o.ID = om.order_id AND om.meta_key = 'customer_country' 
			WHERE o.ID = %d", $order_id);

		$data = $wpdb->get_row( $query, ARRAY_A );

		$this->customer['mem_id']       = ( isset( $data['mem_id'] ) ) ? $data['mem_id'] : null;
		$this->customer['email']        = ( isset( $data['order_email'] ) ) ? $data['order_email'] : '';
		$this->customer['name1']        = ( isset( $data['order_name1'] ) ) ? $data['order_name1'] : '';
		$this->customer['name2']        = ( isset( $data['order_name2'] ) ) ? $data['order_name2'] : null;
		$this->customer['name3']        = ( isset( $data['order_name3'] ) ) ? $data['order_name3'] : null;
		$this->customer['name4']        = ( isset( $data['order_name4'] ) ) ? $data['order_name4'] : null;
		$this->customer['zip']          = ( isset( $data['order_zip'] ) ) ? $data['order_zip'] : null;
		$this->customer['pref']         = ( isset( $data['order_pref'] ) ) ? $data['order_pref'] : '';
		$this->customer['address1']     = ( isset( $data['order_address1'] ) ) ? $data['order_address1'] : '';
		$this->customer['address2']     = ( isset( $data['order_address2'] ) ) ? $data['order_address2'] : null;
		$this->customer['address3']     = ( isset( $data['order_address3'] ) ) ? $data['order_address3'] : null;
		$this->customer['tel']          = ( isset( $data['order_tel'] ) ) ? $data['order_tel'] : '';
		$this->customer['fax']          = ( isset( $data['order_fax'] ) ) ? $data['order_fax'] : null;
		$this->customer['country_code'] = ( isset( $data['order_country'] ) ) ? $data['order_country'] : null;
		$this->customer['country']      = ( isset( $usces_settings['country'][$data['order_country']] ) ) ? $usces_settings['country'][$data['order_country']] : '';

		$this->deliveri = (array) unserialize( $data['order_delivery'] );

		$this->cart = usces_get_ordercartdata( $order_id );

		$this->condition = (array) unserialize( $data['order_condition'] );

		$this->order['ID']               = $order_id;
		$this->order['note']             = ( isset( $data['order_note'] ) ) ? $data['order_note'] : null;
		$this->order['delidue_date']     = ( isset( $data['order_delidue_date'] ) ) ? $data['order_delidue_date'] : null;
		$this->order['delivery_date']    = ( isset( $data['order_delivery_date'] ) ) ? $data['order_delivery_date'] : null;
		$this->order['delivery_time']    = ( isset( $data['order_delivery_time'] ) ) ? $data['order_delivery_time'] : '';
		$this->order['delivery_method']  = ( isset( $data['order_delivery_method'] ) ) ? $data['order_delivery_method'] : -1;
		$this->order['payment_name']     = ( isset( $data['order_payment_name'] ) ) ? $data['order_payment_name'] : '';
		$this->order['item_total_price'] = ( isset( $data['order_item_total_price'] ) ) ? $data['order_item_total_price'] : 0.00;
		$this->order['getpoint']         = ( isset( $data['order_getpoint'] ) ) ? $data['order_getpoint'] : 0;
		$this->order['usedpoint']        = ( isset( $data['order_usedpoint'] ) ) ? $data['order_usedpoint'] : 0;
		$this->order['discount']         = ( isset( $data['order_discount'] ) ) ? $data['order_discount'] : 0.00;
		$this->order['shipping_charge']  = ( isset( $data['order_shipping_charge'] ) ) ? $data['order_shipping_charge'] : 0.00;
		$this->order['cod_fee']          = ( isset( $data['order_cod_fee'] ) ) ? $data['order_cod_fee'] : 0.00;
		$this->order['tax']              = ( isset( $data['order_tax'] ) ) ? $data['order_tax'] : 0.00;
		$this->order['date']             = ( isset( $data['order_date'] ) ) ? $data['order_date'] : '0000-00-00 00:00:00';
		$this->order['modified']         = ( isset( $data['order_modified'] ) ) ? $data['order_modified'] : null;
		$this->order['status']           = ( isset( $data['order_status'] ) ) ? $data['order_status'] : null;
		$this->order['check']            = ( isset( $data['order_check'] ) ) ? $data['order_check'] : null;
		$this->order['total_full_price'] = $data['order_item_total_price'] - $data['order_usedpoint'] + $data['order_discount'] + $data['order_shipping_charge'] + $data['order_cod_fee'] + $data['order_tax'];
		if( $this->order['total_full_price'] < 0 ) $this->order['total_full_price'] = 0;
		$this->order['order_memo'] = $usces->get_order_meta_value( 'order_memo', $order_id );

		$this->reserve = array();
	}
}
