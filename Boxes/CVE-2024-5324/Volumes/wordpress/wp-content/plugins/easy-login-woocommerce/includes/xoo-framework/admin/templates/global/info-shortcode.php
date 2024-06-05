<?php if( empty( $shortcodes ) ) return; ?>

<div class="xoo-sc-shortcodes">
	<h3>Shortcodes</h3>
	<?php foreach ( $shortcodes as $key => $data ): ?>

		<div class="xoo-sc-container">
			<div>
				<span class="xoo-sc-name"><?php echo esc_html( $data['shortcode'] ) ?></span> - <span class="xoo-sc-desc"><?php echo esc_html( $data['desc'] ) ?></span>
			</div>
			<span class="xoo-sc-example">Eg: <?php echo esc_html( $data['example'] ) ?></span>

			<?php if( isset( $data['atts'] ) ): ?>
				<table class="xoo-sc-table">

					<tr>
						<th>Attribute</th>
						<th>Expected</th>
						<th>Default</th>
						<th>Description</th>
					</tr>

					<?php foreach ( $data['atts'] as $attData){
						echo '<tr>';
						foreach ( $attData as $keyTD => $valueTD ) {
							echo '<td>'.esc_html( $valueTD ).'</td>';	
						}
						echo '</tr>';
					} ?>


				</table>
			<?php endif; ?>
		</div>

	<?php endforeach; ?>
</div>