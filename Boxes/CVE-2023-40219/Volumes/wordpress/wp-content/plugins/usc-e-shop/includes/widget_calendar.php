<?php
/**
 * Welcart Calendar Widget
 *
 * @package Welcart
 */

require_once USCES_PLUGIN_DIR . '/classes/calendar.class.php';

/* This month */
list( $todayyy, $todaymm, $todaydd ) = getToday();
$cal1                                = new calendarData();
$cal1->setToday( $todayyy, $todaymm, $todaydd );
$cal1->setCalendarData();
$caption1 = __( 'This month', 'usces' ) . '(' . sprintf( __( '%2$s/%1$s', 'usces' ), $todayyy, $todaymm ) . ')';
/* Next month */
list( $nextyy, $nextmm, $nextdd ) = getAfterMonth( $todayyy, $todaymm, 1, 1 );
$cal2                             = new calendarData();
$cal2->setToday( $nextyy, $nextmm, $nextdd );
$cal2->setCalendarData();
$caption2 = __( 'Next month', 'usces' ) . '(' . sprintf( __( '%2$s/%1$s', 'usces' ), $nextyy, $nextmm ) . ')';
?>
<div class="this-month">
<table cellspacing="0" class="usces_calendar">
<caption><?php echo apply_filters( 'usces_filter_calendar_widget_cap1', $caption1, $todayyy, $todaymm ); ?></caption>
<thead>
	<tr>
		<th><?php esc_html_e( 'Sun', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Mon', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Tue', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Wed', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Thu', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Fri', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Sat', 'usces' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
$cal1_row = $cal1->getRow();
for ( $i = 0; $i < $cal1_row; $i++ ) :
	?>
	<tr>
	<?php
	for ( $d = 0; $d <= 6; $d++ ) :
		$mday = $cal1->getDateText( $i, $d );
		if ( '' != $mday ) :
			$business = ( isset( $usces->options['business_days'][ $todayyy ][ $todaymm ][ $mday ] ) ) ? (int) $usces->options['business_days'][ $todayyy ][ $todaymm ][ $mday ] : 1;
			if ( $mday == $todaydd ) {
				if ( 1 === $business ) {
					$style = ' class="businesstoday"';
				} else {
					$style = ' class="businessday businesstoday"';
				}
			} else {
				$style = ( 1 === $business ) ? '' : ' class="businessday"';
			}
			?>
		<td <?php wel_esc_script_e( $style ); ?>><?php echo esc_html( $mday ); ?></td>
		<?php else : ?>
		<td>&nbsp;</td>
		<?php endif; ?>
	<?php endfor; ?>
	</tr>
<?php endfor; ?>
</tbody>
</table>
</div>
<div class="next-month">
<table cellspacing="0" class="usces_calendar">
<caption><?php echo apply_filters( 'usces_filter_calendar_widget_cap2', $caption2, $nextyy, $nextmm ); ?></caption>
<thead>
	<tr>
		<th><?php esc_html_e( 'Sun', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Mon', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Tue', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Wed', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Thu', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Fri', 'usces' ); ?></th>
		<th><?php esc_html_e( 'Sat', 'usces' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
$cal2_row = $cal2->getRow();
for ( $i = 0; $i < $cal2_row; $i++ ) :
	?>
	<tr>
	<?php
	for ( $d = 0; $d <= 6; $d++ ) :
		$mday = $cal2->getDateText( $i, $d );
		if ( '' != $mday ) :
			$business = ( isset( $usces->options['business_days'][ $nextyy ][ $nextmm ][ $mday ] ) ) ? (int) $usces->options['business_days'][ $nextyy ][ $nextmm ][ $mday ] : 1;
			$style    = ( 1 === $business ) ? '' : ' class="businessday"';
			?>
		<td <?php wel_esc_script_e( $style ); ?>><?php echo esc_html( $mday ); ?></td>
		<?php else : ?>
		<td>&nbsp;</td>
		<?php endif; ?>
	<?php endfor; ?>
	</tr>
<?php endfor; ?>
</tbody>
</table>
</div>
<?php
$afterword = '(<span class="business_days_exp_box businessday">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;' . __( 'Holiday for Shipping Operations', 'usces' ) . ')' . "\n";
echo apply_filters( 'usces_filter_widget_calendar', $afterword );
