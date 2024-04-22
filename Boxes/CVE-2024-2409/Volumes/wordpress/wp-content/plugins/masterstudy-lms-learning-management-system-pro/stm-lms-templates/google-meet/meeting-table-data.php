<?php
/**
 * Google Meeting Table Data.
 */

$meet_post_metas = get_post_meta( $meeting_id );
$meet_name       = get_the_title( $meeting_id ) ?? '';

$meet_gma_date     = $meet_post_metas['stm_gma_start_date'][0] ?? '';
$meet_gma_time     = $meet_post_metas['stm_gma_start_time'][0] ?? '';
$meet_gma_date_end = $meet_post_metas['stm_gma_end_date'][0] ?? '';
$meet_gma_time_end = $meet_post_metas['stm_gma_end_time'][0] ?? '';
$meet_link         = $meet_post_metas['google_meet_link'][0] ?? '';

$combined_datetime = $meet_gma_date . ' ' . $meet_gma_time;
$combined_datetime = intval( $combined_datetime ) / 1000;
$current_timestamp = time(); // Convert current time to milliseconds.

$is_meet_started   = masterstudy_lms_is_google_meet_started( $meeting_id );
$meet_gma_date     = gmdate( 'j M Y', strtotime( gmdate( 'Y-m-d H:i:s', (int) $meet_gma_date / 1000 ) ) );
$meet_gma_time     = gmdate( 'g:i A', strtotime( $meet_gma_time ) );
$meet_gma_date_end = gmdate( 'j M Y', strtotime( gmdate( 'Y-m-d H:i:s', (int) $meet_gma_date_end / 1000 ) ) );
$meet_gma_time_end = gmdate( 'g:i A', strtotime( $meet_gma_time_end ) );
?>
<tr id="gm-<?php echo esc_attr( $meeting_id ); ?>" class="gm-table-data">
	<td class="gmi-meet-title">
		<?php echo esc_html( $meet_name ); ?>
	</td>
	<td>
		<?php echo esc_html( $meet_gma_date . ' — ' . $meet_gma_time ); ?> —
		<br/>
		<?php echo esc_html( $meet_gma_date_end . ' — ' . $meet_gma_time_end ); ?>
	</td>
	<td>
		<a href="<?php echo esc_url( $meet_link ); ?>" target="_blank">
			<?php echo esc_url( $meet_link ); ?>
		</a>
	</td>
	<td class="gm-actions-wrapper">
		<div class="gmi-actions">
			<?php
			if ( $is_meet_started ) {
				?>
				<a class=" button-primary meet-link-start-meeting meeting-links-btn expired-meeting" disabled><?php echo esc_html( 'Expired' ); ?></a>
				<?php
			} else {
				?>
				<a href="<?php echo esc_url( $meet_link ); ?>" target="_blank" class=" button-primary meet-link-start-meeting meeting-links-btn"><?php echo esc_html( 'Start meeting' ); ?></a>
				<a href="#" data-meet-id="<?php echo esc_attr( $meeting_id ); ?>" class="btn meet-links-edit-meeting meeting-links-btn"><?php echo esc_html( 'Edit' ); ?></a>
				<?php
			}
			?>
			<a href="#" class="btn  button-secondary meet-link-trash-btn" data-meet-id="<?php echo esc_attr( $meeting_id ); ?>" data-meet-trash-url="<?php echo esc_url( get_delete_post_link( $meeting_id ) ); ?>">
				<i class="fas fa-trash-alt"></i>
			</a>
		</div>
	</td>
</tr>
