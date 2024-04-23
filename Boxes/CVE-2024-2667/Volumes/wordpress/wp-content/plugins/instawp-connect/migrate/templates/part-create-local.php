<?php
/**
 * Connect to InstaWP Screen
 */

$sh_command = 'wp instawp local push';

?>

<div class="bg-white text-center rounded-md py-16 flex items-center justify-center">
    <div class="w-3/5">

        <div class="mb-2">
            <img src="<?php echo esc_url( instaWP::get_asset_url( 'migrate/assets/images/staging.svg' ) ); ?>" class="mx-auto" alt="">
        </div>

        <div class="text-sm text-gray-700 font-medium text-grayCust-200 mb-1"><?php esc_html_e( 'Support for Local websites is back!', 'instawp-connect' ) ?></div>
        <div class="text-center inline-block text-sm font-normal text-grayCust-50 mb-2"><?php esc_html_e( 'Run the following command on the root folder of your Local website. It will push the website to connected InstaWP account.', 'instawp-connect' ) ?></div>

        <pre>
            <div class="bg-gray-900 rounded-md flex flex-col">
                <div class="flex items-center relative text-gray-300 bg-grayCust-850 px-4 py-2 text-xs font-sans justify-between rounded-t-md select-none">
                    <span>sh</span>
                    <div class="flex gap-1 items-center cursor-pointer instawp-copy-cmd" data-text-to-copy="<?php echo esc_attr( $sh_command ); ?>">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 1.5C5.17155 1.5 4.5 2.17157 4.5 3H7.5C7.5 2.17157 6.82845 1.5 6 1.5ZM3.40135 1.5C3.92006 0.6033 4.88955 0 6 0C7.11045 0 8.07997 0.6033 8.59868 1.5H9.75C10.9927 1.5 12 2.50736 12 3.75V12.75C12 13.9927 10.9927 15 9.75 15H2.25C1.00736 15 0 13.9927 0 12.75V3.75C0 2.50736 1.00736 1.5 2.25 1.5H3.40135ZM3 3H2.25C1.83579 3 1.5 3.33579 1.5 3.75V12.75C1.5 13.1642 1.83579 13.5 2.25 13.5H9.75C10.1642 13.5 10.5 13.1642 10.5 12.75V3.75C10.5 3.33579 10.1642 3 9.75 3H9C9 3.82843 8.32845 4.5 7.5 4.5H4.5C3.67157 4.5 3 3.82843 3 3Z" fill="currentColor"/>
                        </svg>
                        <span class="copy-text" data-text-after-copy="<?php esc_html_e( 'Copied', 'instawp-connect' ); ?>"><?php esc_html_e( 'Copy', 'instawp-connect' ); ?></span>
                    </div>
                </div>
                <div class="p-4 flex overflow-hidden">
                    <span class="text-[#e9950c]"><?php echo esc_attr( $sh_command ); ?></span>
                </div>
            </div>
        </pre>

    </div>
</div>

