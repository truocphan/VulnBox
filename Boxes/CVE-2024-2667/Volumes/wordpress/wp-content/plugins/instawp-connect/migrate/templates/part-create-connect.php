<?php
/**
 * Connect to InstaWP Screen
 */

?>

<div class="bg-white text-center rounded-md py-20 flex items-center justify-center">
    <div>
        <div class="mb-4">
            <img src="<?php echo esc_url( instaWP::get_asset_url( 'migrate/assets/images/createsite.svg' ) ); ?>" class="mx-auto" alt="">
        </div>
        <div class="text-sm font-medium text-grayCust-200 mb-1"><?php esc_html_e( 'InstaWP account is not connected', 'instawp-connect' ); ?></div>
        <div class="text-sm font-normal text-grayCust-50 mb-4"><?php esc_html_e( 'Please authorize your account in order to connect this site and enable staging site creation.', 'instawp-connect' ); ?></div>
        <a class="instawp-button-connect cursor-pointer	px-7 py-3 inline-flex items-center mx-auto rounded-md shadow-sm bg-primary-900 text-white hover:text-white active:text-white focus:text-white focus:shadow-none font-medium text-sm">
            <img src="<?php echo esc_url( instaWP::get_asset_url( 'migrate/assets/images/icon-plus.svg' ) ); ?>" class="mr-2" alt="">
            <span><?php esc_html_e( 'Connect with InstaWP', 'instawp-connect' ); ?></span>
        </a>
    </div>
</div>
