<?php
stm_lms_register_style( 'points_dist' );

$points = stm_lms_point_system();
?>

<div id="stm_lms_points_distribution">

	<h2><?php esc_html_e( 'Points Distribution', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>

	<div class="stm_lms_points_distribution_table">
		<table>
			<thead>
			<tr>
				<th><?php esc_html_e( 'Name', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th><?php esc_html_e( 'Description', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th class="points"><?php esc_html_e( 'Points', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $points as $point ) :
				if ( empty( $point['score'] ) ) {
					continue;
				}
				?>
				<tr>
					<td class="point-label"><?php echo esc_html( $point['label'] ); ?></td>
					<td class="point-title">
					<?php
					if ( ! empty( $point['description'] ) ) {
						echo esc_html( $point['description'] );}
					?>
					</td>
					<td class="points"><strong><?php echo esc_html( $point['score'] ); ?></strong></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>
