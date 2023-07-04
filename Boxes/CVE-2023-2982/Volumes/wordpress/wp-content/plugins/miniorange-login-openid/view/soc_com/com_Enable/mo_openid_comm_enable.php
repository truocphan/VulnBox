<?php
function select_comment_enable() {
	?>
	<div class="mo_openid_table_layout">
		<table>
			<tr>
				<td colspan="2">
					<p>
						<h3><?php echo esc_attr( mo_sl( 'Enable Social Comments' ) ); ?></h3>
						<?php echo esc_attr( mo_sl( 'To enable Social Comments, please select Facebook Comments from' ) ); ?> <strong><?php echo esc_attr( mo_sl( 'Select Applications' ) ); ?></strong>.<?php echo esc_attr( mo_sl( ' Also select one or both of the options from' ) ); ?> <strong><?php echo esc_attr( mo_sl( 'Display Options' ) ); ?></strong>.
						<h3><?php echo esc_attr( mo_sl( 'Add Social Comments' ) ); ?></h3>
						<?php echo esc_attr( mo_sl( 'You can add social comments in the following areas from ' ) ); ?><strong><?php echo esc_attr( mo_sl( 'Display Options' ) ); ?></strong>. <?php echo esc_attr( mo_sl( 'If you require a shortcode, please contact us from the Support form on the right.' ) ); ?>
						<ol>
							<li><?php echo esc_attr( mo_sl( 'Blog Post: This option enables Social Comments on Posts / Blog Post' ) ); ?>.</li>
							<li><?php echo esc_attr( mo_sl( 'Static pages: This option places Social Comments on Pages / Static Pages with comments enabled' ) ); ?>.</li>
						</ol>
					</p>
				</td>
			</tr>
		</table>
	</div>
	<script>
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Enable Social Comments' ) ); ?> ');
	</script>
	<?php
}
