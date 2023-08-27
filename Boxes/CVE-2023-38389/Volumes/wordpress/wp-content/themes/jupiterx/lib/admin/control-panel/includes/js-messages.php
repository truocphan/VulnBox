<?php
/**
 * List of all texts in control panel for translation
 *
 * @package Control Panel
 */

/**
 * This function will be hooked into wp_localize_script in admin/general/enqueue-assets.php
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function jupiterx_adminpanel_textdomain() {
	return array(
		'theme_update_success'                                             => __( 'Theme Updated successfully.', 'jupiterx' ),
		'theme_update_failed'                                              => __( 'Theme update failed. Please try again.', 'jupiterx' ),
		'theme_update_failed_due_to_permission'                            => __( 'Please check file or folder permissions on your server.', 'jupiterx' ),
		'network_active'                                                   => __( 'Network Active', 'jupiterx' ),
		'agree'                                                            => __( 'Agree', 'jupiterx' ),
		'please_note'                                                      => __( 'Please Note:', 'jupiterx' ),
		'any_customisation_you_have_made_to_theme_files_will_be_lost'      => sprintf( __( 'Any customisation you have made to theme files will be lost. <a href="%s" target="_blank">Read More</a>', 'jupiterx' ), 'https://themes.artbees.net/docs/updating-jupiter-x-theme-automatically/' ),

		'restore_settings'                                                 => __( 'Restore Settings', 'jupiterx' ),
		'you_are_trying_to_restore_your_theme_settings_to_this_date'       => __( 'You are trying to Restore your database to this date: ', 'jupiterx' ),
		'yes_install'                                                      => __( 'Yes', 'jupiterx' ),
		'restore'                                                          => __( 'Restore', 'jupiterx' ),
		'reload_page'                                                      => __( 'Reload Page', 'jupiterx' ),
		'uninstalling_template_will_remove_all_your_contents_and_settings' => __( 'Uninstalling template will remove all you current data and settings. Do you want to proceed?', 'jupiterx' ),
		'yes_uninstall'                                                    => __( 'Yes, uninstall ', 'jupiterx' ),
		'template_uninstalled'                                             => __( 'Template uninstalled.', 'jupiterx' ),
		'hooray'                                                           => __( 'All Done!', 'jupiterx' ),
		'template_installed_successfully'                                  => __( 'Template is successfully installed.', 'jupiterx' ),

		'preview'                                                          => __( 'Preview', 'jupiterx' ),
		'import'                                                           => __( 'Import', 'jupiterx' ),
		'downloading_sample_package_data'                                  => __( 'Downloading package', 'jupiterx' ),
		'backup_reset_database'                                            => __( 'Backup Database', 'jupiterx' ),
		'install_required_plugins'                                         => __( 'Install required plugins', 'jupiterx' ),
		'install_sample_data'                                              => __( 'Installing in progress...', 'jupiterx' ),
		'installed'                                                        => __( 'Installed', 'jupiterx' ),
		'include_images_and_videos'                                        => __( 'Include Images and Videos?', 'jupiterx' ),
		'using_ie_edge_not_support'                                        => __( 'Your browser is not supported.', 'jupiterx' ),
		'recommend_to_use_other_browsers'                                  => __( 'Jupiter X detected that you are using IE or EDGE browser that will prevent you from successfully importing media. Please use other modern browsers. {param}', 'jupiterx' ),
		'insufficient_system_resource'                                     => __( 'Insufficient system resource', 'jupiterx' ),
		'insufficient_system_resource_notes'                               => __( 'Your system resource is not enough. Please contact our support or {param} here.', 'jupiterx' ),
		'continue_without_media'                                           => __( 'Continue without Media', 'jupiterx' ),
		'do_not_include'                                                   => __( 'Do not Include', 'jupiterx' ),
		'include'                                                          => __( 'Include', 'jupiterx' ),
		'whoops'                                                           => __( 'Whoops!', 'jupiterx' ),
		'dont_panic'                                                       => __( 'There seems to be an inconsistency in installing procedure. Don\'t panic though here we\'ve listed some possible solutions for you to get back on track.<br>( Warning number : {param}) {param} ', 'jupiterx' ),
		'error_in_network_please_check_your_connection_and_try_again'      => __( 'Error in network , Please check your connection and try again', 'jupiterx' ),
		'incorrect_credentials'                                            => __( 'There was an error connecting to the server, Please verify the settings are correct.', 'jupiterx' ),
		'restore_from_last_backup'                                         => __( 'Restore from Last Backup', 'jupiterx' ),
		'restore_theme_settings_to_this_version'                           => __( 'Restore theme settings to this version', 'jupiterx' ),
		'are_you_sure'                                                     => __( 'Are you sure?', 'jupiterx' ),
		'template_install_intro'                                           => __( 'Choose how you want to import this template:', 'jupiterx' ),
		'template_install_partial_import_title'                            => __( 'Content import', 'jupiterx' ),
		'template_install_partial_import_desc'                             => __( 'Keep your current content, settings, widgets, etc. Only the new page contents will be imported.', 'jupiterx' ),
		'template_install_complete_import_title'                           => __( 'Full import ', 'jupiterx' ),
		'template_install_complete_import_desc'                            => __( 'Your current content, settings, widgets, etc. will be removed and the database will be reset. New page contents and settings will be replaced.', 'jupiterx' ),
		'template_install_complete_import_warning'                         => __( 'All your current content, settings, widgets, etc. will be removed and the new content will be replaced.', 'jupiterx' ),
		'template_install_include_media'                                   => __( 'Include media (Copyrighted).', 'jupiterx' ),
		'are_you_sure_to_continue'                                         => __( 'Are you sure to continue?', 'jupiterx' ),

		'all_done'                                                         => __( 'All Done!', 'jupiterx' ),
		'item_is_successfully_installed'                                   => __( '<strong>{param}</strong> Plugin is successfully installed.', 'jupiterx' ),

		'are_you_sure_you_want_to_remove_plugin'                           => __( 'Are you sure you want to remove <strong>{param}</strong> Plugin? <br> Note that the plugin files will be removed from your server!', 'jupiterx' ),




		'are_you_sure_you_want_to_remove_addon'                            => __( 'Are you sure you want to remove <strong>{param}</strong> Add-on? <br> Note that all any data regarding this add-on will be lost.', 'jupiterx' ),
		'addon_deactivate_successfully'                                    => __( '<strong>{param}</strong> deactivated successfully.', 'jupiterx' ),

		'product_registeration_required'                                   => __( 'Product registration required!', 'jupiterx' ),
		'you_must_register_your_product'                                   => __( 'In order to use this feature you must register your product.', 'jupiterx' ),
		'register_product'                                                 => __( 'Register Product', 'jupiterx' ),
		'registering_theme'                                                => __( 'Registering Jupiter X', 'jupiterx' ),
		'wait_for_api_key_registered'                                      => __( 'Please wait while your API key is being verified.', 'jupiterx' ),
		'discard'                                                          => __( 'Discard', 'jupiterx' ),
		'thanks_registering'                                               => __( 'Thanks for Registration!', 'jupiterx' ),
		'registeration_unsuccessful'                                       => __( 'Oops! Registration was unsuccessful.', 'jupiterx' ),
		'revoke_API_key'                                                   => __( 'Revoke API Key', 'jupiterx' ),
		'you_are_about_to_remove_API_key'                                  => __( 'You are about to remove API key from this website?', 'jupiterx' ),
		'ok'                                                               => __( 'Ok', 'jupiterx' ),
		'cancel'                                                           => __( 'Cancel', 'jupiterx' ),




		'uninstalling_Template'                                            => __( 'Uninstalling Template', 'jupiterx' ),
		'please_wait_for_few_moments'                                      => __( 'Please wait for few moments...', 'jupiterx' ),
		'restoring_database'                                               => __( 'Restoring Database', 'jupiterx' ),
		'remove_image_size'                                                => __( 'Remove Image Size', 'jupiterx' ),
		'are_you_sure_remove_image_size'                                   => __( 'Are you sure you want to remove this image size?', 'jupiterx' ),
		'image_sizes_could_not_be_stored'                                  => __( 'Image sizes could not be stored. Please try again and if issue persists, contact our support.', 'jupiterx' ),
		'download_psd_files'                                               => __( 'Download PSD files', 'jupiterx' ),
		'exporting'                                                        => __( 'Exporting', 'jupiterx' ),
		'export_waiting'                                                   => __( 'Please wait for the export to finish...', 'jupiterx' ),
		'importing'                                                        => __( 'Importing', 'jupiterx' ),
		'import_waiting'                                                   => __( 'Please wait for the import to finish...', 'jupiterx' ),
		'import_select_options'                                            => __( 'Please select only the options which exist in the selected ZIP file.', 'jupiterx' ),
		'site_content'                                                     => __( 'Site Content', 'jupiterx' ),
		'widgets'                                                          => __( 'Widgets', 'jupiterx' ),
		'settings'                                                         => __( 'Settings', 'jupiterx' ),
		'download'                                                         => __( 'Download', 'jupiterx' ),
		'close'                                                            => __( 'Close', 'jupiterx' ),
		'done'                                                             => __( 'Done', 'jupiterx' ),
		'error'                                                            => __( 'Error!', 'jupiterx' ),
		'try_again'                                                        => __( 'Try again', 'jupiterx' ),
		'select'                                                           => __( 'Select', 'jupiterx' ),
		'select_zip_file'                                                  => __( 'Select ZIP file', 'jupiterx' ),
		'successfully_finished'                                            => __( 'has been finished successfully.', 'jupiterx' ),
		'issue_persists'                                                   => __( 'If the issue persists, please contact support.', 'jupiterx' ),
		'template_backup_date'                                             => __( 'Restore database to a backup stored at: ', 'jupiterx' ),
		'add_image_size'                                                   => __( 'Add New Image Size', 'jupiterx' ),
		'image_size_name'                                                  => __( 'Size Name', 'jupiterx' ),
		'image_size_width'                                                 => __( 'Image Width', 'jupiterx' ),
		'image_size_height'                                                => __( 'Image Height', 'jupiterx' ),
		'image_size_crop'                                                  => __( 'Hard Crop?', 'jupiterx' ),
		'save'                                                             => __( 'Save', 'jupiterx' ),
		'edit'                                                             => __( 'Edit', 'jupiterx' ),
		'size_name'                                                        => __( 'Name', 'jupiterx' ),
		'image_size'                                                       => __( 'Size', 'jupiterx' ),
		'crop'                                                             => __( 'Crop', 'jupiterx' ),
		'edit_image_size'                                                  => __( 'Edit Image Size', 'jupiterx' ),
		'saving_image_size'                                                => __( 'Saving Image Sizes', 'jupiterx' ),
		'wait_for_image_size_update'                                       => __( 'Please wait while updating image sizes.', 'jupiterx' ),
		'required'                                                         => __( 'Required', 'jupiterx' ),

		// Plugin manager buttons
		'add'                                                              => __( 'Add', 'jupiterx' ),
		'remove'                                                           => __( 'Remove', 'jupiterx' ),
		'delete'                                                           => __( 'Delete', 'jupiterx' ),
		'install'                                                          => __( 'Install', 'jupiterx' ),
		'activate'                                                         => __( 'Activate', 'jupiterx' ),
		'deactivate'                                                       => __( 'Deactivate', 'jupiterx' ),

		// Common in plugins.
		'continue'                                                         => __( 'Continue ', 'jupiterx' ),
		'upgrade'                                                          => jupiterx_is_premium() ? __( 'Activate to Unlock', 'jupiterx' ) : __( 'Upgrade to Unlock', 'jupiterx' ),
		'upgrade_url'                                                      => jupiterx_is_premium() ? esc_url( admin_url( 'admin.php?page=' . JUPITERX_SLUG ) ) : esc_url( jupiterx_upgrade_link() ),
		'something_went_wrong'                                             => __( 'Something went wrong!', 'jupiterx' ),
		'something_wierd_happened_please_try_again'                        => __( 'Something weird happened, please try again.', 'jupiterx' ),

		// Updating a plugin.
		'update'                                                           => __( 'Update', 'jupiterx' ),
		'plugins'                                                          => __( 'Plugins', 'jupiterx' ),
		'themes'                                                           => __( 'Themes', 'jupiterx' ),
		'update_plugin'                                                    => __( 'Update Plugin', 'jupiterx' ),
		'you_are_about_to_update'                                          => __( 'You are about to update <strong>{param}</strong> plugin', 'jupiterx' ),
		'updating_plugin'                                                  => __( 'Updating Plugin', 'jupiterx' ),
		'wait_for_plugin_update'                                           => __( 'Please wait while updating the plugin...', 'jupiterx' ),
		'plugin_is_successfully_updated'                                   => __( 'Plugin is successfully updated', 'jupiterx' ),
		'plugin_updated_recent_version'                                    => __( '<strong>{param}</strong> is successfully updated to the latest version.', 'jupiterx' ),
		'update_plugin_checker_title'                                      => __( 'Checking conflicts', 'jupiterx' ),
		'update_plugin_checker_progress'                                   => __( 'Please wait, looking for possible conflicts with existing plugins & theme.', 'jupiterx' ),
		'update_plugin_checker_warning'                                    => sprintf( __( '%1$s We have found conflicts on updating this plugin. Please resolve following issues before you continue otherwise it may cause unknown issues.', 'jupiterx' ), '<b>' . __( 'Heads up!', 'jupiterx' ). '</b>'),
		'update_plugin_checker_no_conflict'                                => __( 'No conflict found! Please continue to update the plugin.', 'jupiterx' ),
		'upgrade_to_version'                                               => __( 'Upgrade to version', 'jupiterx' ),

		// Installing a plugin.
		'install_plugin'                                                   => __( 'Install Plugin', 'jupiterx' ),
		'you_are_about_to_install'                                         => __( 'You are about to install <strong>{param}</strong> plugin.', 'jupiterx' ),
		'are_you_sure_you_want_to_install'                                 => __( 'Are you sure you want to install <strong>{param}</strong>?', 'jupiterx' ),
		'installing_plugin'                                                => __( 'Installing Plugin...', 'jupiterx' ),
		'wait_for_plugin_install'                                          => __( 'Please wait while the plugin is being installed.', 'jupiterx' ),
		'plugin_is_successfully_installed'                                 => __( 'Plugin is installed successfully.', 'jupiterx' ),
		'plugin_installed_successfully_message'                            => __( 'Latest version of <strong>{param}</strong> is installed successfully.', 'jupiterx' ),

		// Activating a plugin.
		'activating_notice'                                                => __( 'Activating Notice', 'jupiterx' ),
		'are_you_sure_you_want_to_activate'                                => __( 'Are you sure you want to activate <strong>{param}</strong>?', 'jupiterx' ),
		'activating_plugin'                                                => __( 'Activating Plugin', 'jupiterx' ),
		'wait_for_plugin_activation'                                       => __( 'Please wait while the plugin going to be activated...', 'jupiterx' ),
		'item_is_successfully_activated'                                   => __( '<strong>{param}</strong> Plugin is successfully activated.', 'jupiterx' ),

		// Deactivating a plugin
		'important_notice'                                                 => __( 'Important Notice', 'jupiterx' ),
		'are_you_sure_you_want_to_deactivate'                              => __( 'Are you sure you want to deactivate <strong>{param}</strong> plugin?', 'jupiterx' ),
		'deactivating_plugin'                                              => __( 'Deactivating Plugin', 'jupiterx' ),
		'wait_for_plugin_deactivation'                                     => __( 'Please wait while the plugin going to be deactivated...', 'jupiterx' ),
		'deactivating_notice'                                              => __( 'Deactivation Notice', 'jupiterx' ),
		'plugin_deactivate_successfully'                                   => __( 'Plugin successfully deactivated.', 'jupiterx' ),

		// Deleting a plugin.
		'delete_plugin'                                                    => __( 'Delete Plugin', 'jupiterx' ),
		'deleting_plugin'                                                  => __( 'Deleting plugin...', 'jupiterx' ),
		'you_are_about_to_delete'                                          => __( 'You are about to delete <strong>{param}</strong>', 'jupiterx' ),
		'wait_for_plugin_delete'                                           => __( 'Please wait while the plugin is being deleted.', 'jupiterx' ),
		'plugin_is_successfully_deleted'                                   => __( 'Plugin is deleted successfully.', 'jupiterx' ),
		'plugin_deleted_successfully_message'                              => __( '<strong>{param}</strong> is deleted successfully.', 'jupiterx' ),

		// Plugin activation limit warning.
		'plugin_limit_warning'                                             => __( 'Important Notification', 'jupiterx' ),
		'plugin_limit_warning_message'                                     => __( 'Activating too many plugins can cause performance issues to your website. We highly recommend activating only those plugins you really need and deactivate unnecessary ones.', 'jupiterx' ),
		'learn_more'                                                       => __( 'Learn More', 'jupiterx' ),

		// Pro badge.
		'pro_badge_tooltip_title'                                          => jupiterx_is_premium() ? __( 'Activate to Unlock', 'jupiterx' ) : __( 'Upgrade to Unlock', 'jupiterx' ),

		// Theme version change
		'apikey_domain_match_error'                                        => __( 'API key and the domain are not matching', 'jupiterx' ),

		// New Theme registration
		'license_manager_registration_title'                               => __( 'Register license', 'jupiterx' ),
		'license_manager_email_address'                                    => __( 'Email Address', 'jupiterx' ),
		'license_manager_add_purchase_code'                                => __( 'Add Envato license key', 'jupiterx' ),
		'license_manager_insert_api'                                       => __( 'Insert Artbees API Key', 'jupiterx' ),
		'license_manager_add_api'                                          => __( 'Add Artbees API key', 'jupiterx' ),
		'submit'                                                           => __( 'Submit', 'jupiterx' ),
		'license_manager_insert_purchase_code'                             => __( 'Insert Envato purchase code', 'jupiterx' ),
		'license_manager_revoking_error'                                   => __( 'Deactivation error', 'jupiterx' ),
		'wait_for_api_key_revoke'                                          => __( 'Please wait while your API key is being revoked.', 'jupiterx' ),
		'license_manager_revoking_title'                                   => __( 'Deactivating license', 'jupiterx' ),

		// DB Manager
		'restore_ok'                                                       => __( 'You have successfully restored your database.', 'jupiterx' ),

		// Additional
		'all'                                                              => __( 'All', 'jupiterx' ),
		'active'                                                           => __( 'Active', 'jupiterx' ),
		'inactive'                                                         => __( 'Inactive', 'jupiterx' ),
		'updates_available'                                                => __( 'Updates Available', 'jupiterx' ),
		'optional'                                                         => __( 'Optional', 'jupiterx' ),
		'recommended'                                                      => __( 'Recommended', 'jupiterx' ),
		'completed'                                                        => __( 'Completed', 'jupiterx' ),
		'installing_plugin_progress'                                       => __( 'Installing plugin..', 'jupiterx' ),
		'activate_required_plugins'                                        => __( 'Activate Required Plugins', 'jupiterx' ),
		'activate_error'                                                   => __( 'Activate Error', 'jupiterx' ),
		'activating_plugins'                                               => __( 'Activating Plugins', 'jupiterx' ),
		'activating_plugins_successful'                                    => __( 'Activating Plugins Successful', 'jupiterx' ),
		'activating_plugin_progress'                                       => __( 'Activating plugin..', 'jupiterx' ),
		'update_all_plugins'                                               => __( 'Update All Plugins', 'jupiterx' ),
		'updating_plugins'                                                 => __( 'Updating Plugins', 'jupiterx' ),
		'updating_plugins_successful'                                      => __( 'Updating Plugins Successful', 'jupiterx' ),
		'updating_plugin_progress'                                         => __( 'Updating plugin..', 'jupiterx' ),
		'plugins_notice'                                                   => __( 'Please activate only required plugins and keep the unneeded plugins deactivated. Too many active plugin may slow down your site as each adds more functionality to your website.', 'jupiterx' ),
		'confirm_activate_plugins'                                         => __( 'Are you sure you want to activate required plugins?', 'jupiterx' ),
		'confirm_update_plugins'                                           => __( 'Are you sure you want to update all plugins?', 'jupiterx' ),
		'install_error'                                                    => __( 'Install Error', 'jupiterx' ),
		'install_plugin_failed'                                            => __( 'Plugin installation failed, please refresh the page and try again.', 'jupiterx' ),
		'update_error'                                                     => __( 'Update Error', 'jupiterx' ),
		'update_plugin_failed'                                             => __( 'Plugin update failed, please refresh the page and try again.', 'jupiterx' ),
		'api_request_error'                                                => __( 'Could not connect to Artbees server, please try again and if the issue persists contact support.', 'jupiterx' ),
		'on'                                                               => __( 'On', 'jupiterx' ),
		'off'                                                              => __( 'Off', 'jupiterx' ),
	);
}
