<?php
/**
 * Migrate template - Sync
 */

global $staging_sites, $instawp_settings;

if ( instawp()->is_staging && instawp()->is_parent_on_local ) { ?>
    <div class="nav-item-content sync bg-white rounded-md p-6">
        <div class="data-listening">
            <div class="w-full">
                <div class="text-center">
                    <div class="mb-5 mt-3">
                        <svg class="mx-auto" width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.6458 1.00577C15.8462 1.54778 16.7587 3.17728 18.5837 6.43627L22.9441 14.2227C24.6984 17.3554 25.5756 18.9217 25.4289 20.2044C25.3009 21.3234 24.707 22.3366 23.7932 22.995C22.7458 23.7498 20.9505 23.7498 17.3601 23.7498H8.63928C5.04883 23.7498 3.25361 23.7498 2.2062 22.995C1.29238 22.3366 0.698519 21.3234 0.570511 20.2044C0.423789 18.9217 1.30094 17.3554 3.05525 14.2227L7.41565 6.43627C9.24069 3.17728 10.1532 1.54778 11.3536 1.00577C12.4001 0.533234 13.5993 0.533234 14.6458 1.00577ZM13.7497 9.99978C13.7497 9.58556 13.4139 9.24978 12.9997 9.24978C12.5854 9.24978 12.2497 9.58556 12.2497 9.99978V13.9998C12.2497 14.414 12.5854 14.7498 12.9997 14.7498C13.4139 14.7498 13.7497 14.414 13.7497 13.9998V9.99978ZM12.9997 16.2498C12.3093 16.2498 11.7497 16.8094 11.7497 17.4998C11.7497 18.1901 12.3093 18.7498 12.9997 18.7498C13.69 18.7498 14.2497 18.1901 14.2497 17.4998C14.2497 16.8094 13.69 16.2498 12.9997 16.2498Z"
                                    fill="#F43F5E"/>
                        </svg>
                    </div>
                    <div class="text-sm font-medium text-grayCust-200 mb-2"><?php esc_html_e( 'This is a staging site of a local site', 'instawp-connect' ); ?></div>
                    <div class="text-sm font-normal text-grayCust-50 mb-1"><?php esc_html_e( 'Sync from a staging website to a local website is not supported.', 'instawp-connect' ); ?></div>
                </div>
            </div>
        </div>
    </div>
	<?php
	return;
}

$syncing_status      = (bool) InstaWP_Setting::get_args_option( 'instawp_is_event_syncing', $instawp_settings );
$events              = $syncing_status ? InstaWP_Sync_DB::total_events() : array();
$parent_connect_data = InstaWP_Setting::get_option( 'instawp_sync_parent_connect_data' );
$staging_sites       = empty( $staging_sites ) || ! is_array( $staging_sites ) ? array() : $staging_sites;

if ( ! empty( $parent_connect_data ) ) {
	if ( ! array_key_exists( 'url', $parent_connect_data ) ) {
		$parent_connect_data['url'] = InstaWP_Setting::get_args_option( 'domain', $parent_connect_data, '' );
	}
	if ( ! array_key_exists( 'connect_id', $parent_connect_data ) ) {
		$parent_connect_data['connect_id'] = InstaWP_Setting::get_args_option( 'id', $parent_connect_data, '' );
	}

	$staging_sites[] = $parent_connect_data;
}

?>
<div class="nav-item-content sync bg-white rounded-md p-6">
	<?php if ( empty( $events ) ) : ?>
        <div class="data-listening">
            <div class="w-full">
                <div class="text-center ">
                    <div class="mb-4">
                        <svg width="38" class="mx-auto" height="30" viewBox="0 0 38 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 17H25H13ZM19 11V23V11ZM1 25V5C1 3.93913 1.42143 2.92172 2.17157 2.17157C2.92172 1.42143 3.93913 1 5 1H17L21 5H33C34.0609 5 35.0783 5.42143 35.8284 6.17157C36.5786 6.92172 37 7.93913 37 9V25C37 26.0609 36.5786 27.0783 35.8284 27.8284C35.0783 28.5786 34.0609 29 33 29H5C3.93913 29 2.92172 28.5786 2.17157 27.8284C1.42143 27.0783 1 26.0609 1 25Z" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-sm font-medium text-grayCust-200 mb-2"><?php esc_html_e( 'No Data found!', 'instawp-connect' ); ?></div>
                    <div class="text-sm font-normal text-grayCust-50 mb-1"><?php esc_html_e( 'Start Listening for Changes', 'instawp-connect' ); ?></div>
                    <div class="instawp_is_event_syncing">
                        <label class="toggle-control">
                            <input type="checkbox" <?php checked( $syncing_status, 1 ); ?> name="instawp_is_event_syncing" id="instawp_is_event_syncing" class="toggle-checkbox">
                            <div class="toggle-switch"></div>
                            <span class="toggle-label" data-on="1" data-off="0"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
	<?php else : ?>
        <div class="sync-listining1">
            <div class="w-full">
                <div class="events-head">
                    <div class="events-head-left flex items-center justify-center">
                        <div class="text-grayCust-200 text-lg font-medium"><?php esc_html_e( 'Listening for Changes', 'instawp-connect' ); ?></div>
                        <label class="toggle-control instawp_is_event_syncing">
                            <input type="checkbox" class="toggle-checkbox" <?php checked( $syncing_status, 1 ); ?>>
                            <div class="toggle-switch"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between flex-row-reverse gap-3">
                        <button type="button" class="bg-white py-2 px-2 border rounded shadow instawp-refresh-events">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" style="fill: #005e54;" d="M13.5 2c-5.629 0-10.212 4.436-10.475 10h-3.025l4.537 5.917 4.463-5.917h-2.975c.26-3.902 3.508-7 7.475-7 4.136 0 7.5 3.364 7.5 7.5s-3.364 7.5-7.5 7.5c-2.381 0-4.502-1.119-5.876-2.854l-1.847 2.449c1.919 2.088 4.664 3.405 7.723 3.405 5.798 0 10.5-4.702 10.5-10.5s-4.702-10.5-10.5-10.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="bulk-sync-popup-btn text-white bg-[#005e54] font-medium py-2 px-3 rounded text-center inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" style="fill: #fff;"
                                        d="M1.59995 0.800049C2.09701 0.800049 2.49995 1.20299 2.49995 1.70005V3.59118C3.64303 2.42445 5.23642 1.70005 6.99995 1.70005C9.74442 1.70005 12.0768 3.45444 12.9412 5.90013C13.1069 6.36877 12.8612 6.88296 12.3926 7.0486C11.924 7.21425 11.4098 6.96862 11.2441 6.49997C10.6259 4.75097 8.95787 3.50005 6.99995 3.50005C5.52851 3.50005 4.22078 4.20657 3.39937 5.30005H6.09995C6.59701 5.30005 6.99995 5.70299 6.99995 6.20005C6.99995 6.6971 6.59701 7.10005 6.09995 7.10005H1.59995C1.10289 7.10005 0.699951 6.6971 0.699951 6.20005V1.70005C0.699951 1.20299 1.10289 0.800049 1.59995 0.800049ZM1.6073 8.95149C2.07594 8.78585 2.59014 9.03148 2.75578 9.50013C3.37396 11.2491 5.04203 12.5 6.99995 12.5C8.47139 12.5 9.77912 11.7935 10.6005 10.7L7.89995 10.7C7.40289 10.7 6.99995 10.2971 6.99995 9.80005C6.99995 9.30299 7.40289 8.90005 7.89995 8.90005H12.3999C12.6386 8.90005 12.8676 8.99487 13.0363 9.16365C13.2051 9.33243 13.3 9.56135 13.3 9.80005V14.3C13.3 14.7971 12.897 15.2 12.4 15.2C11.9029 15.2 11.5 14.7971 11.5 14.3V12.4089C10.3569 13.5757 8.76348 14.3 6.99995 14.3C4.25549 14.3 1.92309 12.5457 1.05867 10.1C0.893024 9.63132 1.13866 9.11714 1.6073 8.95149Z"/>
                            </svg>
                            <span class="sync-text"><?php esc_html_e( 'Sync All', 'instawp-connect' ); ?></span>
                        </button>
                        <div class="select-ct <?php echo empty( $staging_sites ) ? 'hidden' : '' ?>">
                            <select id="staging-site-sync" data-page="instawp">
								<?php foreach ( $staging_sites as $site ) : ?>
									<?php if ( isset( $site['url'] ) && isset( $site['connect_id'] ) ) : ?>
                                        <option value="<?php echo esc_attr( $site['connect_id'] ) ?>"><?php echo esc_html( $site['url'] ); ?></option>
									<?php endif ?>
								<?php endforeach ?>
                            </select>
                        </div>
                        <div class="select-ct <?php echo empty( $staging_sites ) ? 'hidden' : '' ?>">
                            <select id="filter-sync-events">
                                <option value="all"><?php esc_html_e( 'All', 'instawp-connect' ); ?></option>
                                <option value="pending"><?php esc_html_e( 'Pending', 'instawp-connect' ); ?></option>
                                <option value="completed"><?php esc_html_e( 'Completed', 'instawp-connect' ); ?></option>
                            </select>
                        </div>
                        <button type="button" id="instawp-delete-events" class="bg-white hover:bg-red-100 text-red-400 py-2 px-2 border border-red-400 rounded shadow hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-my-2 -mx-6 overflow-x-auto lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <form id="event-form" method="POST">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 py-3 uppercase text-center text-sm font-medium text-grayCust-900 w-0.5"><input type="checkbox" name="select_all_event" id="select-all-event"></th>
                                            <th scope="col" class="px-3 py-3 uppercase text-left text-sm font-medium text-grayCust-900"><?php esc_html_e( 'event', 'instawp-connect' ); ?></th>
                                            <th scope="col" class="px-3 py-3 text-left uppercase text-sm font-medium text-grayCust-900"><?php esc_html_e( 'event details', 'instawp-connect' ); ?></th>
                                            <th scope="col" class="px-3 py-3 text-left uppercase text-sm font-medium text-grayCust-900"><?php esc_html_e( 'Date', 'instawp-connect' ); ?></th>
                                            <th scope="col" class="px-3 py-3 text-center uppercase text-sm font-medium text-grayCust-900"><?php esc_html_e( 'Status', 'instawp-connect' ); ?></th>
                                            <!-- <th scope="col" class="px-6 py-4 text-center uppercase text-sm font-medium text-grayCust-900"><?php esc_html_e( 'Actions', 'instawp-connect' ); ?></th> -->
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white" id="part-sync-results">
                                        <tr>
                                            <td colspan="5" class="event-sync-cell loading"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <nav id="event-sync-pagination-area" class="flex items-center justify-between border-t border-gray-200 mx-9 my-5 hidden">
                            <div id="event-sync-pagination"></div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="bulk-sync-popup" data-sync-type="">
            <div class="instawp-popup-main">
                <div class="instawppopwrap">
                    <div class="topinstawppopwrap">
                        <h3><?php esc_html_e( 'Preparing Events for Sync', 'instawp-connect' ); ?></h3>
                        <div class="destination_form">
                            <label for="destination-site"><?php esc_html_e( 'Destination site', 'instawp-connect' ); ?></label>
                            <select id="destination-site">
								<?php foreach ( $staging_sites as $site ) : ?>
									<?php if ( isset( $site['url'] ) && isset( $site['connect_id'] ) ) : ?>
                                        <option value="<?php echo esc_attr( $site['connect_id'] ) ?>"><?php echo esc_html( $site['url'] ); ?></option>
									<?php endif ?>
								<?php endforeach ?>
                            </select>
                        </div>
                        <div class="rounded-xl w-full text-bg py-4 px-4 border mt-5 bg-green-50">
                            <div class="progress-wrapper">
                                <div class="w-100 text-left font-medium event-progress-text">
									<?php esc_html_e( 'Sync not initiated', 'instawp-connect' ); ?>
                                </div>
                                <div class="w-full text-bg py-1 flex items-center mb-2 border-b-[1px] mb-6 pb-3">
                                    <div class="w-full bg-gray-200 rounded-md event-progress-bar">
                                        <div class="instawp-progress-bar h-2 bg-primary-900 rounded-md"></div>
                                    </div>
                                    <div class="progress-text text-grayCust-650 text-sm font-medium"></div>
                                </div>
                            </div>
                            <div class="instawp_category rounded-xl">
                                <div id="event-type-list" class="instawpcatlftcol bulk-events-info bg-[#fff] relative instawp-box-loading">

                                </div>
                                <div class="instawpcatlftcol selected-events-info">
                                    <ul class="list">
                                        <li><span class="post-change">0</span><?php esc_html_e( 'post change events', 'instawp-connect' ); ?></li>
                                        <li><span class="post-delete">0</span><?php esc_html_e( 'post delete events', 'instawp-connect' ); ?></li>
                                        <li><span class="post-trash">0</span><?php esc_html_e( 'post trash events', 'instawp-connect' ); ?></li>
                                        <li><span class="others">0</span><?php esc_html_e( 'other events', 'instawp-connect' ); ?></li>
                                    </ul>
                                </div>
                                <div class="instawpcatrgtcol sync_process bg-[#fff]">
                                    <ul>
                                        <li class="step-1 process_pending"><?php esc_html_e( 'Packing things', 'instawp-connect' ); ?></li>
                                        <li class="step-2 process_pending"><?php esc_html_e( 'Pushing', 'instawp-connect' ); ?></li>
                                        <li class="step-3 process_pending"><?php esc_html_e( 'Merging to destination', 'instawp-connect' ); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="sync_error_success_msg"></div>
                        <div class="sync_message_main textarea_json destination_form mt-6">
                            <label for="sync_message"><?php esc_html_e( 'Message:', 'instawp-connect' ); ?></label>
                            <input type="hidden" id="id_syncIds" value=""/>
                            <textarea id="sync_message" name="sync_message" rows="4"></textarea>
                        </div>
                        <div class="instawp_buttons mt-6">
                            <div class="bulk-close-btn"><a class="cancel-btn close" href="javascript:void(0);"><?php esc_html_e( 'Cancel', 'instawp-connect' ); ?></a></div>
                            <div class="bulk-sync-btn"><a class="changes-btn sync-changes-btn disabled" href="javascript:void(0);"><span><?php esc_html_e( 'Sync', 'instawp-connect' ); ?></span></a></div>
                        </div>
                    </div>
                    <div><input type="hidden" id="selected_events" name="selected_events" value=""></div>
                </div>
            </div>
        </div>
	<?php endif ?>
</div>