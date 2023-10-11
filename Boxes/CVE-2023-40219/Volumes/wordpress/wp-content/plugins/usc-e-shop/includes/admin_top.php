<script type="text/javascript">jQuery(function($){uscesInformation.getinfo2();});</script>
<?php
global $wpdb;
$display_mode = $this->options['display_mode'];
$stocs = usces_get_stocs();
$items_num = $this->get_items_num();
?>
<div class="wrap">
<div class="usces_admin">

<h1>Welcart Shop <?php _e('Home','usces'); ?></h1>
<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>

<div class="usces_admin_right">

<div class="usces_side_box">
<h5><?php _e('Display Modes','usces'); ?>:</h5>
<div class="dispmode <?php echo esc_attr( $display_mode ); ?>"><?php echo esc_html( $this->display_mode[ $display_mode ] ); ?></div>
<?php if ( $display_mode == 'Promotionsale' ) : ?>
<span><?php _e('Special Benefits', 'usces'); ?>:</span><?php echo esc_html($this->options["campaign_privilege"]); ?> (<?php if($this->options["campaign_privilege"] == 'discount'){echo esc_html($this->options["privilege_discount"]).__('% Discount', 'usces');}elseif($this->options["campaign_privilege"] == 'point'){echo esc_html($this->options["privilege_point"]).__(" times (limited to members)", 'usces');} ?>) <br />
<span><?php _e('applied material', 'usces'); ?>:</span><?php echo esc_html(get_cat_name($this->options["campaign_category"])); ?><br />
<span><?php _e('Period', 'usces'); ?>:</span><?php echo esc_html( $this->options["campaign_schedule"]['start']['year'] ); ?>/<?php echo esc_html( $this->options["campaign_schedule"]['start']['month'] ); ?>/<?php echo esc_html( $this->options["campaign_schedule"]['start']['day'] ); ?><?php _e(' - ', 'usces'); ?><?php echo esc_html( $this->options["campaign_schedule"]['end']['year'] ); ?>/<?php echo esc_html( $this->options["campaign_schedule"]['end']['month'] ); ?>/<?php echo esc_html( $this->options["campaign_schedule"]['end']['day'] ); ?>
<?php endif; ?>
</div>

<?php if( !(defined('USCES_ADMIN_INFO') && !USCES_ADMIN_INFO) && 7 < $this->user_level ) : ?>
<div id= "wc_information" class="chui"></div>
<?php endif; ?>
<?php
$payment_method = usces_get_system_option( 'usces_payment_method', 'settlement' );
$deactivate = array();
foreach( $payment_method as $settlement => $payment ) {
	if( 'deactivate' == $payment['use'] ) {
		$deactivate[] = $payment['name'];
	}
}
if( 0 < count( $deactivate ) ) :
?>
<div id="wc_payment_method_info" class="info">
<div><?php _e( 'Stopped payment method', 'usces' ); ?></div>
	<?php
	$c = '';
	foreach ( $deactivate as $payment ) {
		echo esc_html( $c . $payment );
		$c = ',';
	}
	?>
</div>
<?php
endif;
?>
<?php do_action( 'usces_action_admin_top_sidebar' ); ?>

</div><!--usces_admin_right-->

<div class="usces_admin_left">

<?php if( 4 < $this->user_level ): ?>
<h4><?php _e('number & amount of order', 'usces'); ?></h4>
<div class="usces_box">
<table class="dashboard">
<tr>
<th><?php _e('Currency','usces'); ?> : <?php usces_crcode(); ?></th><th><?php _e('number of order', 'usces'); ?></th><th><?php _e('amount of order', 'usces'); ?></th>
</tr>
<tr>
<td><?php _e('today', 'usces'); ?> : </td><td class="bignum"><?php echo number_format($this->get_order_num('today')); ?></td><td class="bignum"><?php usces_crform( $this->get_order_amount('today'), true, false ); ?></td>
</tr>
<tr>
<td><?php _e('This month', 'usces'); ?> : </td><td class="bignum"><?php echo number_format($this->get_order_num('thismonth')); ?></td><td class="bignum"><?php usces_crform( $this->get_order_amount('thismonth'), true, false ); ?></td>
</tr>
<tr>
<td><?php _e('Same date in last year', 'usces'); ?> : </td><td class="bignum"><?php echo number_format($this->get_order_num('lastyear')); ?></td><td class="bignum"><?php usces_crform( $this->get_order_amount('lastyear'), true, false ); ?></td>
</tr>
</table>
</div>
<?php endif; ?>
<?php do_action( 'usces_action_admintop_box1' ); ?>

<h4><?php _e('information for registration of items', 'usces'); ?></h4>
<div class="usces_box">
<table class="dashboard">
<tr>
<th><?php _e('number of item', 'usces'); ?></th><th colspan="5"><?php _e('SKU total number', 'usces'); ?></th>
</tr>
<tr>
<td rowspan="3" class="bignum"><?php echo number_format($items_num); ?></td><td colspan="5" class="bignum"><?php echo number_format(array_sum($stocs)); ?></td>
</tr>
<tr>
<?php 
foreach($this->zaiko_status as $value):
?>
<th><?php if($value == __('OK', 'usces')) {echo __('In Stock', 'usces');}else{echo esc_html( $value );} ?></th>
<?php
endforeach;
?>
</tr>
<tr>
<?php
foreach($this->zaiko_status as $stock_key => $value): $count = isset($stocs[$stock_key]) ? $stocs[$stock_key] : 0; ?>
<td class="bignum"><?php echo number_format($count); ?></td>
<?php
endforeach;
unset($stocs);
?>
</tr>
<tr>
<th colspan="6"><?php _e('List of items without stock', 'usces'); ?></th>
</tr>
<?php
$zerostoc_items = usces_get_non_zerostoc_items();
foreach((array)$zerostoc_items as $item): ?>
<tr>
<td colspan="6"><a href="<?php echo ( site_url() . '/wp-admin/admin.php?page=usces_itemedit&action=edit&post=' . $item['ID'] ); ?>"><?php echo ( esc_html( $item['name'] ) . ' ' . esc_html( $item['code'] ) ); ?></a></td>
</tr>
<?php
endforeach;
unset($non_stoc_skus);
?>
</table>
</div>

<?php do_action( 'usces_action_admintop_box2' ); ?>

<?php if( 2 < $this->user_level ): ?>
<h4><?php _e('Your environment', 'usces'); ?></h4>
<div class="usces_box">
<table class="dashboard">
<tr>
<th>&nbsp;</th><th colspan="2"><?php _e('Software Version', 'usces'); ?></th>
</tr>
<tr>
<td><?php _e('Server', 'usces'); ?></td><td colspan="2"><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
</tr>
<tr>
<td>MySQL</td><td colspan="2"><?php echo esc_html( $wpdb->dbh->server_info ); ?></td>
</tr>
<tr>
<?php
$get_ini = ini_get_all();
$essential_extentions = array('simplexml', 'curl', 'gd', 'json', 'mbstring', 'mysql', 'openssl');
?>
<td>PHP</td><td colspan="2"><?php echo esc_html( phpversion() ); ?> memory[global]:<?php echo esc_html( $get_ini['memory_limit']['global_value'] ); ?> [locale]:<?php echo esc_html( $get_ini['memory_limit']['local_value'] ); ?> [usage]:<?php echo esc_html( (int) ( memory_get_peak_usage() / 1048576 ) ); ?>M<br />
<?php
foreach($essential_extentions as $key => $esext){
	if (extension_loaded($esext)) {
		if($key){
			echo  ',&nbsp;' . esc_html( $esext );
		}else{
			echo esc_html( $esext );
		}
    }
}
?>
</td>
</tr>
</table>
</div>
<?php endif; ?>
</div>
<!--usces_admin_left-->
</div><!--usces_admin-->
</div><!--wrap-->