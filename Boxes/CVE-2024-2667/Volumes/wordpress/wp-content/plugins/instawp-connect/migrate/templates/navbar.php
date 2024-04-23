<?php
/**
 * Migrate template - Main
 */

$api_domain       = InstaWP_Setting::get_api_domain();
$current_tab      = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
$plugin_nav_items = InstaWP_Setting::get_plugin_nav_items();

if ( ! in_array( $current_tab, array_keys( $plugin_nav_items ) ) ) {
	$current_tab = '';
}

?>

<div class="flex border-b justify-between shadow-md rounded-tl-lg rounded-tr-lg border-grayCust-100 instawp-current-tab" current-tab="<?php echo esc_attr( $current_tab ); ?>">
    <div class="flex items-center nav-items">
		<?php foreach ( $plugin_nav_items as $item_key => $item ) {
			$icon  = isset( $item['icon'] ) ? $item['icon'] : '';
			$label = isset( $item['label'] ) ? $item['label'] : '';

			printf( '<div id="%s" class="nav-item"><a class="flex items-center px-4 py-5 border-b-2 border-transparent hover:text-primary-900 text-sm font-medium">%s<span>%s</span></a></div>', $item_key, $icon, esc_html( $label ) );
		} ?>
    </div>
    <div class="flex items-center text-sm font-medium">
		<?php if ( empty( InstaWP_Setting::get_api_key() ) ) : ?>
            <div class="flex items-center text-grayCust-1300"><?php esc_html_e( 'Please connect InstaWP account', 'instawp-connect' ); ?></div>
            <button type="button" class="instawp-button-connect px-4 rounded-lg py-2 border border-primary-900 text-primary-900 text-sm font-medium ml-3 mr-3">
                <span><?php esc_html_e( 'Connect', 'instawp-connect' ); ?></span>
            </button>
		<?php else : ?>
            <span class="w-1 h-1 <?= strpos( $api_domain, 'stage' ) !== false ? 'bg-amber-600' : 'bg-primary-700'; ?> rounded-full mr-2"></span>
            <a href="<?php echo esc_url( sprintf( '%s/connects/%s/dashboard', InstaWP_Setting::get_api_domain(), instawp()->connect_id ) ); ?>" target="_blank" class="mr-4 focus:ring-0 hover:ring-0 focus:outline-0 hover:outline-0 <?= strpos( $api_domain, 'stage' ) !== false ? 'text-amber-600 hover:text-amber-600 focus:text-amber-600' : 'text-primary-700 hover:text-primary-700 focus:text-primary-700'; ?>"><?php esc_html_e( 'Your website is connected', 'instawp-connect' ); ?></a>
		<?php endif; ?>
    </div>
</div>