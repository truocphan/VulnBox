<?php

if ( jupiterx_is_pro() || jupiterx_is_premium() ) {
	include 'home.php';
	return;
}

?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-comparison">
	<table class="jupiterx-comparison-table">
		<tr>
			<th class="jupiterx-ct-first-col"></th>
			<th class="jupiterx-ct-free">
				<span class="jupiterx-ct-title"><?php esc_html_e( 'Free', 'jupiterx' ); ?></span>
				<span class="jupiterx-ct-desc"><?php esc_html_e( 'Active now', 'jupiterx' ); ?></span>
			</th>
			<th class="jupiterx-ct-pro">
				<span class="jupiterx-ct-title"><?php esc_html_e( 'Pro', 'jupiterx' ); ?></span>
				<span class="jupiterx-ct-desc"><?php esc_html_e( 'Upgrade for $59', 'jupiterx' ); ?></span>
				<i class="jupiterx-icon-pro"></i>
			</th>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Pre-made website templates', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( '1 template', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( '280+ templates', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Content block templates', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Elements', 'jupiterx' ); ?></td>
			<td><?php _e( '30', 'jupiterx' ); ?></td>
			<td><?php _e( '100+', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Advanced shop customisation', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Advanced blog', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Premium slideshow plugins', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Tabbed Content', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Visual effects', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Custom post types & fields', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Popup', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'AJAX filters', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Mega menu', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Custom header', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Custom title bar', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Custom footer', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Form builder', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Adobe fonts (Typekit)', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Blog single templates', 'jupiterx' ); ?></td>
			<td><?php _e( '1', 'jupiterx' ); ?></td>
			<td><?php _e( '3', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Blog single customisation', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Modest', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Deep', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Product list customisation', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Product page customisation', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Checkout and Cart pages customisation', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Product page templates', 'jupiterx' ); ?></td>
			<td><?php _e( '1', 'jupiterx' ); ?></td>
			<td><?php _e( '8', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Portfolio customisation', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Modest', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Deep', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Tracking codes', 'jupiterx' ); ?></td>
			<td><i class="jupiterx-icon-times"></i></td>
			<td><i class="jupiterx-icon-check"></i></td>
		</tr>
		<tr>
			<td class="jupiterx-ct-feature-label"><?php esc_html_e( 'Support', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Community support', 'jupiterx' ); ?></td>
			<td><?php esc_html_e( 'Premium support', 'jupiterx' ); ?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="jupiterx-ct-text-center jupiterx-ct-bg-normal">
				<a class="btn btn-large jupiterx-btn-upgrade-pro" href="<?php echo esc_attr( jupiterx_upgrade_link( 'comparison' ) ); ?>" target="_blank"><?php esc_html_e( 'Upgrade now', 'jupiterx' ); ?></a>
				<a href="https://themes.artbees.net/docs/jupiter-x-pro" target="_blank" class="jupiterx-ct-learn-more"><?php esc_html_e( 'Learn more about Jupiter X Pro', 'jupiterx' ); ?></a>
			</td>
		</tr>
	</table>
</div>
