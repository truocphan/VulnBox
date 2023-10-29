<?php // User Submitted Posts - Plugin Display

if (!defined('ABSPATH')) die();

function usp_render_form() {
	
	global $usp_options, $wpdb; 
	
	$version_previous = isset($usp_options['usp_version']) ? esc_attr($usp_options['usp_version']) : USP_VERSION;
	
	$display_alert = (isset($usp_options['version_alert']) && $usp_options['version_alert']) ? ' style="display:none;"' : ' style="display:block;"'; 
	
	$custom_styles = (isset($usp_options['usp_form_version']) && $usp_options['usp_form_version'] !== 'custom') ? 'display: none;' : 'display: block;';
	
	?>
	
	<style type="text/css">#mm-plugin-options .usp-custom-form-info { <?php echo $custom_styles; ?> }</style>
	
	<div id="mm-plugin-options" class="wrap">
		
		<h1><?php echo USP_PLUGIN; ?> <small><?php echo 'v'. USP_VERSION; ?></small></h1>
		<div id="mm-panel-toggle"><a href="#"><?php esc_html_e('Toggle all panels', 'usp'); ?></a></div>
		
		<form method="post" action="options.php">
			<?php settings_fields('usp_plugin_options'); ?>
			
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					
					<div id="mm-panel-overview" class="postbox">
						<h2><?php esc_html_e('Overview', 'usp'); ?></h2>
						<div class="toggle<?php if (isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
							<div class="mm-panel-overview">
								<p>
									<strong><abbr title="<?php echo USP_PLUGIN; ?>"><?php esc_html_e('USP', 'usp'); ?></abbr></strong> <?php esc_html_e('enables your visitors to submit posts and upload images from the front-end of your site. ', 'usp'); ?> 
									<?php esc_html_e('For more features and unlimited forms, check out', 'usp'); ?> <strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?></a></strong> 
									<?php esc_html_e('&mdash; the ultimate solution for user-generated content.', 'usp'); ?>
								</p>
								<ul>
									<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/user-submitted-posts/"><?php esc_html_e('Plugin Homepage', 'usp'); ?>&nbsp;&raquo;</a></li>
									<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/user-submitted-posts/#installation"><?php esc_html_e('Documentation', 'usp'); ?>&nbsp;&raquo;</a></li>
									<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/user-submitted-posts/"><?php esc_html_e('Support Forum', 'usp'); ?>&nbsp;&raquo;</a></li>
								</ul>
								<p>
									<?php esc_html_e('If you like this plugin, please', 'usp'); ?> 
									<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/user-submitted-posts/reviews/?rate=5#new-post" title="<?php esc_attr_e('THANK YOU for your support!', 'usp'); ?>"><?php esc_html_e('give it a 5-star rating', 'usp'); ?>&nbsp;&raquo;</a>
								</p>
								<a target="_blank" rel="noopener noreferrer" class="mm-panel-overview-pro" href="https://plugin-planet.com/usp-pro/" title="<?php esc_attr_e('Unlimited front-end forms', 'usp'); ?>"><?php esc_html_e('Get USP Pro', 'usp'); ?></a>
							</div>
						</div>
					</div>
					
					<div id="mm-panel-primary" class="postbox">
						
						<h2><?php esc_html_e('Plugin Settings', 'usp'); ?></h2>
						
						<div class="toggle<?php if (!isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
							
							<h3><?php esc_html_e('Form Fields', 'usp'); ?></h3>
							<div class="mm-table-wrap mm-table-less-padding">
								<div class="mm-section-desc"><?php esc_html_e('Choose fields to display on the front-end form.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<?php 
										
										echo usp_form_field_options(array('usp_name',     esc_html__('User Name',     'usp')));
										echo usp_form_field_options(array('usp_email',    esc_html__('User Email',    'usp')));
										echo usp_form_field_options(array('usp_url',      esc_html__('User URL',      'usp')));
										echo usp_form_field_options(array('usp_title',    esc_html__('Post Title',    'usp')));
										echo usp_form_field_options(array('usp_tags',     esc_html__('Post Tags',     'usp')));
										echo usp_form_field_options(array('usp_category', esc_html__('Post Category', 'usp')));
										echo usp_form_field_options(array('usp_content',  esc_html__('Post Content',  'usp')));
										
										echo usp_form_field_options_custom('1');
										echo usp_form_field_options_custom('2');
										echo usp_form_field_options_captcha();
										echo usp_form_field_options_recaptcha();
										echo usp_form_field_options_images();
										
									?>
									<tr>
										<th class="some-padding" scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td class="some-padding">
											<span class="mm-item-caption">
												<?php esc_html_e('For unlimited fields, check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('General Settings', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure general settings. Note: the default settings work fine for most cases.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_form_version]"><?php esc_html_e('Form Style', 'usp'); ?></label></th>
										<td>
											<?php echo usp_form_display_options(); ?>
											
											<div class="usp-custom-form-info">
												<p><?php esc_html_e('With this option, you can copy the plugin&rsquo;s default templates:', 'usp'); ?></p>
												<ul>
													<li><code>/resources/usp.css</code></li>
													<li><code>/views/submission-form.php</code></li>
												</ul>
												<p><?php esc_html_e('..and upload them into a directory named', 'usp'); ?> <code>/usp/</code> <?php esc_html_e('in your theme:', 'usp'); ?></p>
												<ul>
													<li><code>/wp-content/themes/your-theme/usp/usp.css</code></li>
													<li><code>/wp-content/themes/your-theme/usp/submission-form.php</code></li>
												</ul>
												<p>
													<?php esc_html_e('That will enable you to customize the form and styles as desired. For more info, check out the "Custom Submission Form" section in the', 'usp'); ?> 
													<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/user-submitted-posts/#installation"><?php esc_html_e('Installation Docs', 'usp'); ?></a>. 
													<?php esc_html_e('FYI: here is a', 'usp'); ?> <a target="_blank" rel="noopener noreferrer" href="https://m0n.co/e"><?php esc_html_e('list of USP CSS selectors', 'usp'); ?>&nbsp;&raquo;</a> 
												</p>
											</div>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_include_js]"><?php esc_html_e('Include JavaScript', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_include_js]" <?php if (isset($usp_options['usp_include_js'])) checked('1', $usp_options['usp_include_js']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Include required JavaScript files (recommended)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_display_url]"><?php esc_html_e('Targeted Loading', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[usp_display_url]" value="<?php if (isset($usp_options['usp_display_url'])) echo esc_attr($usp_options['usp_display_url']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('By default, CSS &amp; JavaScript files are loaded on every page. To load only on specific page(s), enter the URL(s) here. Use commas to separate multiple URLs. Leave blank to load on all pages.', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_post_type]"><?php esc_html_e('Post Type', 'usp'); ?></label></th>
										<td>
											<?php echo usp_post_type_options(); ?>
											<span class="mm-item-caption"><?php esc_html_e('Post Type for submitted posts', 'usp'); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[number-approved]"><?php esc_html_e('Post Status', 'usp'); ?></label></th>
										<td>
											<?php echo usp_post_status_options(); ?>
											<span class="mm-item-caption"><?php esc_html_e('Post Status for submitted posts', 'usp'); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[redirect-url]"><?php esc_html_e('Redirect URL', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[redirect-url]" value="<?php if (isset($usp_options['redirect-url'])) echo esc_attr($usp_options['redirect-url']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Redirect user to this URL after post submission (leave blank to stay on current page)', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[success-message]"><?php esc_html_e('Success Message', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[success-message]"><?php if (isset($usp_options['success-message'])) echo esc_textarea($usp_options['success-message']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Message displayed after successful post submission (basic markup allowed)', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[error-message]"><?php esc_html_e('Error Message', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[error-message]"><?php if (isset($usp_options['error-message'])) echo esc_textarea($usp_options['error-message']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Error message displayed if post submission fails (basic markup allowed)', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_form_content]"><?php esc_html_e('Custom Content', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[usp_form_content]"><?php if (isset($usp_options['usp_form_content'])) echo esc_textarea($usp_options['usp_form_content']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Optional markup/text to include before the submission form (leave blank to disable)', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_richtext_editor]"><?php esc_html_e('Rich Text Editor', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_richtext_editor]" <?php if (isset($usp_options['usp_richtext_editor'])) checked('1', $usp_options['usp_richtext_editor']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Enable RTE/Visual Editor for the Post Content field', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[titles_unique]"><?php esc_html_e('Unique Titles', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[titles_unique]" <?php if (isset($usp_options['titles_unique'])) checked('1', $usp_options['titles_unique']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Require submitted post titles to be unique (useful for preventing multiple/duplicate posts)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[disable_required]"><?php esc_html_e('Disable Required', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[disable_required]" <?php if (isset($usp_options['disable_required'])) checked('1', $usp_options['disable_required']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Disable all required attributes on form fields (useful for troubleshooting error messages)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[disable_ip_tracking]"><?php esc_html_e('Disable IP Tracking', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[disable_ip_tracking]" <?php if (isset($usp_options['disable_ip_tracking'])) checked('1', $usp_options['disable_ip_tracking']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Do not collect or store any user IP address (useful for complying with privacy regulations)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[enable_shortcodes]"><?php esc_html_e('Enable Shortcodes', 'usp'); ?></label></th>
										<td><input name="usp_options[enable_shortcodes]" type="checkbox" value="1" <?php if (isset($usp_options['enable_shortcodes'])) checked('1', $usp_options['enable_shortcodes']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Enable shortcodes in widgets. By default, WordPress does not enable shortcodes in widgets. ', 'usp'); ?>
										<?php esc_html_e('This setting enables any/all shortcodes in widgets (even shortcodes from other plugins).', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For more options and features, check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('User Settings', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure user settings.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[author]"><?php esc_html_e('Assigned Author', 'usp'); ?></label></th>
										<td>
											<?php echo usp_post_author_options(); ?>
											<span class="mm-item-caption"><?php esc_html_e('Specify the user that should be assigned as author for submitted posts', 'usp'); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_use_author]"><?php esc_html_e('Registered Username', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_use_author]" <?php if (isset($usp_options['usp_use_author'])) checked('1', $usp_options['usp_use_author']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Use the user&rsquo;s registered username for the Name field (valid when the user is logged in to WordPress)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_use_email]"><?php esc_html_e('Registered Email', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_use_email]" <?php if (isset($usp_options['usp_use_email'])) checked('1', $usp_options['usp_use_email']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Use the user&rsquo;s registered email as the value of the Email field (valid when the user is logged in to WordPress)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_use_url]"><?php esc_html_e('Registered URL', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_use_url]" <?php if (isset($usp_options['usp_use_url'])) checked('1', $usp_options['usp_use_url']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Use the user&rsquo;s Profile URL as the value of the URL field (valid when the user is logged in to WordPress)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[logged_in_users]"><?php esc_html_e('Require User Login', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[logged_in_users]" <?php if (isset($usp_options['logged_in_users'])) checked('1', $usp_options['logged_in_users']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Require users to be logged in to WordPress to view/submit the form', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[disable_author]"><?php esc_html_e('Disable Replace Author', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[disable_author]" <?php if (isset($usp_options['disable_author'])) checked('1', $usp_options['disable_author']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Do not replace post author with submitted user name', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For more options and features, check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('Email Alerts', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure email notifications for new submitted posts.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_email_alerts]"><?php esc_html_e('Receive Email Alert', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_email_alerts]" <?php if (isset($usp_options['usp_email_alerts'])) checked('1', $usp_options['usp_email_alerts']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Send email alerts for new post submissions', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_email_html]"><?php esc_html_e('Enable HTML Format', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_email_html]" <?php if (isset($usp_options['usp_email_html'])) checked('1', $usp_options['usp_email_html']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Enable HTML format for email alert messages', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_email_address]"><?php esc_html_e('Email Address for Alerts', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[usp_email_address]" value="<?php if (isset($usp_options['usp_email_address'])) echo esc_attr($usp_options['usp_email_address']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Additional recipients for email alerts. Use commas to separate multiple addresses.', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_email_from]"><?php esc_html_e('Email &ldquo;From&rdquo; Address', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[usp_email_from]" value="<?php if (isset($usp_options['usp_email_from'])) echo esc_attr($usp_options['usp_email_from']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Custom address for the email &ldquo;From&rdquo; header (see plugin FAQs for info).', 'usp'); ?> 
										<?php esc_html_e('If multiple addresses are specified for the previous setting, include an equal number of addresses for this setting (in the same order).', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[email_alert_subject]"><?php esc_html_e('Email Alert Subject', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[email_alert_subject]" value="<?php if (isset($usp_options['email_alert_subject'])) echo esc_attr($usp_options['email_alert_subject']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Subject line for email alerts. Leave blank to use default subject line. You may include any of the following variables:', 'usp'); ?> 
										<code>%%post_title%%</code>, <code>%%post_content%%</code>, <code>%%post_author%%</code>, <code>%%blog_name%%</code>, <code>%%blog_url%%</code>, <code>%%post_url%%</code>, <code>%%admin_url%%</code>, 
										<code>%%edit_link%%</code>, <code>%%delete_link%%</code>, <code>%%user_email%%</code>, <code>%%user_url%%</code>, <code>%%custom_field%%</code>, <code>%%custom_field_2%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[email_alert_message]"><?php esc_html_e('Email Alert Message', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[email_alert_message]"><?php if (isset($usp_options['email_alert_message'])) echo esc_textarea($usp_options['email_alert_message']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Message for email alerts. Leave blank to use default message. You may include any of the following variables:', 'usp'); ?> 
										<code>%%post_title%%</code>, <code>%%post_content%%</code>, <code>%%post_author%%</code>, <code>%%blog_name%%</code>, <code>%%blog_url%%</code>, <code>%%post_url%%</code>, <code>%%admin_url%%</code>, 
										<code>%%edit_link%%</code>, <code>%%delete_link%%</code>, <code>%%user_email%%</code>, <code>%%user_url%%</code>, <code>%%custom_field%%</code>, <code>%%custom_field_2%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For more', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-include-post-info-email-alert/"><?php esc_html_e('email-alert options', 'usp'); ?></a>, 
												<?php esc_html_e('check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('Categories &amp; Tags', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure settings for the Category and Tag fields.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('Categories', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc usp-cat-toggle-link"><a href="#"><?php esc_html_e('Show categories', 'usp'); ?></a></div>
											<div class="usp-cat-toggle-div default-hidden"><?php echo usp_post_category_options(); ?></div>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[multiple-cats]"><?php esc_html_e('Multiple Categories', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[multiple-cats]" <?php if (isset($usp_options['multiple-cats'])) checked('1', $usp_options['multiple-cats']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Enable users to select multiple categories', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_use_cat]"><?php esc_html_e('Hidden/Default Category', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_use_cat]" <?php if (isset($usp_options['usp_use_cat'])) checked('1', $usp_options['usp_use_cat']); ?> /> 
										<span class="mm-item-caption"><?php esc_html_e('Use a hidden field for the post category. This hides the category field and sets its value via the next option.', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_use_cat_id]"><?php esc_html_e('Category ID for Hidden Field', 'usp'); ?></label></th>
										<td><input class="usp-input-short" type="text" size="45" maxlength="100" name="usp_options[usp_use_cat_id]" value="<?php if (isset($usp_options['usp_use_cat_id'])) echo esc_attr($usp_options['usp_use_cat_id']); ?>" /> 
										<span class="mm-item-caption"><?php esc_html_e('Specify category ID(s) to use for &ldquo;Hidden/Default Category&rdquo; (separate multiple IDs with commas)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_existing_tags]"><?php esc_html_e('Use Existing Tags', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_existing_tags]" <?php if (isset($usp_options['usp_existing_tags'])) checked('1', $usp_options['usp_existing_tags']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Display a select/dropdown menu of existing tags (valid when Tag field is displayed on the form)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For more', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-add-categories/"><?php esc_html_e('category', 'usp'); ?></a> <?php esc_html_e('and', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-add-tags/"><?php esc_html_e('tag', 'usp'); ?></a> <?php esc_html_e('options, check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3 id="usp-custom-field-1"><?php esc_html_e('Custom Field 1', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure Custom Field #1.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_name]"><?php esc_html_e('Custom Field Name', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[custom_name]" value="<?php if (isset($usp_options['custom_name'])) echo esc_attr($usp_options['custom_name']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Use only alphanumeric, underscores, and dashes. If unsure, use the default name:', 'usp'); ?> <code>usp_custom_field</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_label]"><?php esc_html_e('Custom Field Label', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[custom_label]" value="<?php if (isset($usp_options['custom_label'])) echo esc_attr($usp_options['custom_label']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('This will be displayed as the field label on the form. Default: Custom Field 1', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-custom-fields/"><?php esc_html_e('unlimited custom fields', 'usp'); ?></a>, 
												<?php esc_html_e('check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3 id="usp-custom-field-2"><?php esc_html_e('Custom Field 2', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure Custom Field #2.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_name_2]"><?php esc_html_e('Custom Field Name', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[custom_name_2]" value="<?php if (isset($usp_options['custom_name_2'])) echo esc_attr($usp_options['custom_name_2']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Use only alphanumeric, underscores, and dashes. If unsure, use the default name:', 'usp'); ?> <code>usp_custom_field_2</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_label_2]"><?php esc_html_e('Custom Field Label', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[custom_label_2]" value="<?php if (isset($usp_options['custom_label_2'])) echo esc_attr($usp_options['custom_label_2']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('This will be displayed as the field label on the form. Default: Custom Field 2', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-custom-fields/"><?php esc_html_e('unlimited custom fields', 'usp'); ?></a>, 
												<?php esc_html_e('check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('Custom Checkbox', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('By default, this displays an &ldquo;Agree to Terms&rdquo; checkbox. Customize as desired.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_checkbox]"><?php esc_html_e('Display Checkbox', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[custom_checkbox]" <?php if (isset($usp_options['custom_checkbox'])) checked('1', $usp_options['custom_checkbox']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Display custom checkbox on the submission form', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_checkbox_name]"><?php esc_html_e('Checkbox Name', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[custom_checkbox_name]" value="<?php if (isset($usp_options['custom_checkbox_name'])) echo esc_attr($usp_options['custom_checkbox_name']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Use only alphanumeric, underscores, and dashes. If unsure, use the default name:', 'usp'); ?> <code>usp_custom_checkbox</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_checkbox_err]"><?php esc_html_e('Checkbox Error', 'usp'); ?></label></th>
										<td><input type="text" size="45" maxlength="200" name="usp_options[custom_checkbox_err]" value="<?php if (isset($usp_options['custom_checkbox_err'])) echo esc_attr($usp_options['custom_checkbox_err']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Error message displayed if user does not check the box', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[custom_checkbox_text]"><?php esc_html_e('Checkbox Text', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[custom_checkbox_text]"><?php if (isset($usp_options['custom_checkbox_text'])) echo esc_textarea($usp_options['custom_checkbox_text']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Text displayed next to checkbox. Tip: use curly brackets to output angle brackets, for example:', 'usp'); ?> <code>{img}</code> = <code>&lt;img&gt;</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For', 'usp'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-custom-checkbox-fields/"><?php esc_html_e('unlimited checkbox fields', 'usp'); ?></a>, 
												<?php esc_html_e('check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3 id="usp-challenge-question"><?php esc_html_e('Challenge Question', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Add a challenge question to help stop spam. Tip: make the question easy to answer for any human.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_question]"><?php esc_html_e('Challenge Question', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[usp_question]" value="<?php if (isset($usp_options['usp_question'])) echo esc_attr($usp_options['usp_question']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('To prevent spam, enter a question that users must answer before submitting the form', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_response]"><?php esc_html_e('Challenge Response', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[usp_response]" value="<?php if (isset($usp_options['usp_response'])) echo esc_attr($usp_options['usp_response']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Enter the *only* correct answer to the challenge question', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_casing]"><?php esc_html_e('Case-sensitivity', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_casing]" <?php if (isset($usp_options['usp_casing'])) checked('1', $usp_options['usp_casing']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Make the challenge response case-sensitive', 'usp'); ?></span></td>
									</tr>
								</table>
							</div>
							
							<h3 id="usp-recaptcha"><?php esc_html_e('Google reCaptcha', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('To enable Google reCaptcha, enter your public and private keys.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[recaptcha_public]"><?php esc_html_e('Public Key', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[recaptcha_public]" value="<?php if (isset($usp_options['recaptcha_public'])) echo esc_attr($usp_options['recaptcha_public']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Enter your Public Key', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[recaptcha_private]"><?php esc_html_e('Private Key', 'usp'); ?></label></th>
										<td><input type="text" size="45" name="usp_options[recaptcha_private]" value="<?php if (isset($usp_options['recaptcha_private'])) echo esc_attr($usp_options['recaptcha_private']); ?>" />
										<div class="mm-item-caption"><?php esc_html_e('Enter your Private Key', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[recaptcha_version]"><?php esc_html_e('reCaptcha Version', 'usp'); ?></label></th>
										<td>
											<?php echo usp_form_field_recaptcha(); ?>
											<span class="mm-item-caption"><?php esc_html_e('Choose reCaptcha version', 'usp'); ?></span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3 id="usp-image-uploads"><?php esc_html_e('Image Uploads', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Configure settings for image uploads.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_featured_images]"><?php esc_html_e('Featured Image', 'usp'); ?></label></th>
										<td><input type="checkbox" value="1" name="usp_options[usp_featured_images]" <?php if (isset($usp_options['usp_featured_images'])) checked('1', $usp_options['usp_featured_images']); ?> />
										<span class="mm-item-caption"><?php esc_html_e('Use submitted images as Featured Images. Requires theme support for Featured Images (aka Post Thumbnails)', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_featured_image_default]"><?php esc_html_e('Default Featured Image', 'usp'); ?></label></th>
										<td>
											<input id="upload_image" class="usp-upload-image" type="text" size="36" name="usp_options[usp_featured_image_default]" value="<?php if (isset($usp_options['usp_featured_image_default'])) echo esc_attr($usp_options['usp_featured_image_default']); ?>" />
											<input id="upload_image_button" class="button" type="button" value="Upload Image" />
											<div class="mm-item-caption"><?php esc_html_e('Enter URL or click button to upload/choose an image (optional)', 'usp'); ?></div>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[upload-message]"><?php esc_html_e('Upload Message', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[upload-message]"><?php if (isset($usp_options['upload-message'])) echo esc_textarea($usp_options['upload-message']); ?></textarea>
										<div class="mm-item-caption"><?php esc_html_e('Message displayed next to the file upload field (basic markup allowed)', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[usp_add_another]"><?php esc_html_e('&ldquo;Add another image&rdquo; link', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[usp_add_another]"><?php if (isset($usp_options['usp_add_another'])) echo esc_textarea($usp_options['usp_add_another']); ?></textarea>
										<div class="mm-item-caption"><?php esc_html_e('Custom markup for the &ldquo;Add another image&rdquo; link. Leave blank to use the default markup (recommended).', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[min-images]"><?php esc_html_e('Minimum number of images', 'usp'); ?></label></th>
										<td><input name="usp_options[min-images]" type="number" class="small-text" step="1" min="0" max="999" maxlength="3" value="<?php if (isset($usp_options['min-images'])) echo esc_attr($usp_options['min-images']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Minimum number of images for each submitted post', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[max-images]"><?php esc_html_e('Maximum number of images', 'usp'); ?></label></th>
										<td><input name="usp_options[max-images]" type="number" class="small-text" step="1" min="0" max="999" maxlength="3" value="<?php if (isset($usp_options['max-images'])) echo esc_attr($usp_options['max-images']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Maximum number of images for each submitted post', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[min-image-width]"><?php esc_html_e('Minimum image width', 'usp'); ?></label></th>
										<td><input name="usp_options[min-image-width]" type="number" class="small-text" step="1" min="0" max="999999999" maxlength="9" value="<?php if (isset($usp_options['min-image-width'])) echo esc_attr($usp_options['min-image-width']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Minimum width (in pixels) for uploaded images', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[min-image-height]"><?php esc_html_e('Minimum image height', 'usp'); ?></label></th>
										<td><input name="usp_options[min-image-height]" type="number" class="small-text" step="1" min="0" max="999999999" maxlength="9" value="<?php if (isset($usp_options['min-image-height'])) echo esc_attr($usp_options['min-image-height']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Minimum height (in pixels) for uploaded images', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[max-image-width]"><?php esc_html_e('Maximum image width', 'usp'); ?></label></th>
										<td><input name="usp_options[max-image-width]" type="number" class="small-text" step="1" min="0" max="999999999" maxlength="9" value="<?php if (isset($usp_options['max-image-width'])) echo esc_attr($usp_options['max-image-width']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Maximum width (in pixels) for uploaded images', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[max-image-height]"><?php esc_html_e('Maximum image height', 'usp'); ?></label></th>
										<td><input name="usp_options[max-image-height]" type="number" class="small-text" step="1" min="0" max="999999999" maxlength="9" value="<?php if (isset($usp_options['max-image-height'])) echo esc_attr($usp_options['max-image-height']); ?>" />
										<span class="mm-item-caption"><?php esc_html_e('Maximum height (in pixels) for uploaded images', 'usp'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('Enable users to upload other file types (like PDF, Word, Zip, videos, and more), check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<h3><?php esc_html_e('Front-end Display', 'usp'); ?></h3>
							<div class="mm-table-wrap">
								<div class="mm-section-desc"><?php esc_html_e('Automatically display images and more.', 'usp'); ?></div>
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_images]"><?php esc_html_e('Image Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display submitted images:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('images'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_image_markup]"><?php esc_html_e('Image Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_image_markup]"><?php if (isset($usp_options['auto_image_markup'])) echo esc_textarea($usp_options['auto_image_markup']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for each submitted image. You may include any of the following variables:', 'usp'); ?> 
										<code>%%width%%</code>, <code>%%height%%</code>, <code>%%thumb%%</code>, <code>%%medium%%</code>, <code>%%large%%</code>, <code>%%full%%</code>, <code>%%custom%%</code>, 
										<code>%%title%%</code>, <code>%%title_parent%%</code>, <code>%%author%%</code>, <code>%%url%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_email]"><?php esc_html_e('Email Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display submitted email address:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('email'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_email_markup]"><?php esc_html_e('Email Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_email_markup]"><?php if (isset($usp_options['auto_email_markup'])) echo esc_textarea($usp_options['auto_email_markup']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for submitted email address. You may include any of the following variables:', 'usp'); ?> 
										<code>%%email%%</code>, <code>%%author%%</code>, <code>%%title%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_name]"><?php esc_html_e('Name Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display submitted author/name:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('name'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_name_markup]"><?php esc_html_e('Name Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_name_markup]"><?php if (isset($usp_options['auto_name_markup'])) echo esc_textarea($usp_options['auto_name_markup']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for submitted author/name. You may include', 'usp'); ?> 
										<code>%%author%%</code> <?php esc_html_e('to display the submitted name.', 'usp'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_url]"><?php esc_html_e('URL Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display submitted URL:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('url'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_url_markup]"><?php esc_html_e('URL Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_url_markup]"><?php if (isset($usp_options['auto_url_markup'])) echo esc_textarea($usp_options['auto_url_markup']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for submitted URL. You may include any of the following variables:', 'usp'); ?> 
										<code>%%url%%</code>, <code>%%author%%</code>, <code>%%title%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_custom]"><?php esc_html_e('Custom Field 1 Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display Custom Field 1:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('custom'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_custom_markup]"><?php esc_html_e('Custom Field 1 Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_custom_markup]"><?php if (isset($usp_options['auto_custom_markup'])) echo esc_textarea($usp_options['auto_custom_markup']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for Custom Field 1. You may include any of the following variables:', 'usp'); ?> 
										<code>%%custom_label%%</code>, <code>%%custom_name%%</code>, <code>%%custom_value%%</code>, <code>%%author%%</code>, <code>%%title%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_display_custom_2]"><?php esc_html_e('Custom Field 2 Display', 'usp'); ?></label></th>
										<td>
											<div class="mm-item-desc"><?php esc_html_e('Display Custom Field 2:', 'usp'); ?></div>
											<?php echo usp_auto_display_options('custom_2'); ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="usp_options[auto_custom_markup_2]"><?php esc_html_e('Custom Field 2 Markup', 'usp'); ?></label></th>
										<td><textarea class="textarea" rows="3" cols="50" name="usp_options[auto_custom_markup_2]"><?php if (isset($usp_options['auto_custom_markup_2'])) echo esc_textarea($usp_options['auto_custom_markup_2']); ?></textarea> 
										<div class="mm-item-caption"><?php esc_html_e('Markup for Custom Field 2. You may include any of the following variables:', 'usp'); ?> 
										<code>%%custom_label_2%%</code>, <code>%%custom_name_2%%</code>, <code>%%custom_value_2%%</code>, <code>%%author%%</code>, <code>%%title%%</code></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description"><?php esc_html_e('More Options', 'usp'); ?></label></th>
										<td>
											<span class="mm-item-caption">
												<?php esc_html_e('For more options and features, check out', 'usp'); ?> 
												<strong><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a></strong>
											</span>
										</td>
									</tr>
								</table>
							</div>
							
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save All Changes', 'usp'); ?>">
							
						</div>
						
					</div>
					
					<div id="mm-panel-secondary" class="postbox">
						
						<h2><?php esc_html_e('Display the Form', 'usp'); ?></h2>
						
						<div class="toggle default-hidden">
							
							<h3><?php esc_html_e('Display the submit-post form', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('USP enables you to display a post-submission form anywhere on your site.', 'usp'); ?></p>
								<p><?php esc_html_e('Use the shortcode to display the form on any WP Post or Page:', 'usp'); ?></p>
								<pre>[user-submitted-posts]</pre>
								<p><?php esc_html_e('Or, use the template tag to display the form anywhere in your theme template:', 'usp'); ?></p>
								<pre>&lt;?php if (function_exists('user_submitted_posts')) user_submitted_posts(); ?&gt;</pre>
								<p>
									<?php esc_html_e('Want more than one form? Create unlimited submission forms, registration forms, contact forms and more with', 'usp'); ?> 
									<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/"><?php esc_html_e('USP Pro', 'usp'); ?>&nbsp;&raquo;</a>
								</p>
							</div>
							
							<h3><?php esc_html_e('Display the login/register form', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('You also can display a simple form that enables users to log in, register, or reset their password.', 'usp'); ?></p>
								<p><?php esc_html_e('Use the shortcode to display the form on any WP Post or Page:', 'usp'); ?></p>
								<pre>[usp-login-form]</pre>
								<p><?php esc_html_e('Or, use the template tag to display the form anywhere in your theme template:', 'usp'); ?></p>
								<pre>&lt;?php if (function_exists('usp_login_form')) usp_login_form(); ?&gt;</pre>
								<p><?php esc_html_e('The login/register form displays as a tabbed interface, so users can switch between login, register, and reset password.', 'usp'); ?></p>
							</div>
							
							<h3><?php esc_html_e('Display user-submitted posts', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('Use this shortcode to display a list of submitted posts on any WP Post or Page:', 'usp'); ?></p>
								<pre>[usp_display_posts]</pre>
								<p><?php esc_html_e('Or, use the template tag to display a list of submitted posts anywhere in your theme template:', 'usp'); ?></p>
								<pre>&lt;?php if (function_exists('usp_display_posts')) echo usp_display_posts(array('userid' => 'all', 'numposts' => -1)); ?&gt;</pre>
								<p><?php esc_html_e('Here are some examples showing how to configure this shortcode:', 'usp'); ?></p>
<pre>[usp_display_posts]                           : default displays all submitted posts by all authors
[usp_display_posts userid="1"]                : displays all submitted posts by registered user with ID = 1
[usp_display_posts userid="Pat Smith"]        : displays all submitted posts by author name "Pat Smith"
[usp_display_posts userid="all"]              : displays all submitted posts by all users/authors
[usp_display_posts userid="all" numposts="5"] : limit to 5 posts from all users</pre>
								<p>
									<strong><?php esc_html_e('Tip:', 'usp'); ?></strong> 
									<?php esc_html_e('The pro version provides many more options for this shortcode.', 'usp'); ?> 
									<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-display-list-submitted-posts/"><?php esc_html_e('Learn&nbsp;more', 'usp'); ?>&nbsp;&raquo;</a>
								</p>
							</div>
							
							<h3><?php esc_html_e('Display image gallery', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('Use this shortcode to display a gallery of uploaded images for each submitted post:', 'usp'); ?></p>
								<pre>[usp_gallery]</pre>
								<p><?php esc_html_e('Or, use the template tag to display an image gallery anywhere in your theme template:', 'usp'); ?></p>
								<pre>&lt;?php if (function_exists('usp_get_images')) $images = usp_get_images(); foreach ($images as $image) echo $image; ?&gt;</pre>
								<p><?php esc_html_e('You can customize using any of the follwing attributes:', 'usp'); ?></p>
<pre>$size   = image size as thumbnail, medium, large or full -> default = thumbnail
$before = text/markup displayed before the image URL     -> default = {a href='%%url%%'}{img src='
$after  = text/markup displayed after the image URL      -> default = ' /}{/a}
$number = the number of images to display for each post  -> default = false (display all)
$postId = an optional post ID to use                     -> default = false (uses global/current post)</pre>
								<p><strong><?php esc_html_e('Notes:', 'usp'); ?></strong></p>
								<ul>
									<li>
										<?php esc_html_e('Use curly brackets', 'usp'); ?> <code>{</code> <code>}</code> <?php esc_html_e('to output angle brackets', 'usp'); ?> 
										<code>&lt;</code> <code>&gt;</code> <?php esc_html_e('in', 'usp'); ?> <code>before</code> <?php esc_html_e('and', 'usp'); ?> 
										<code>after</code> <?php esc_html_e('attributes', 'usp'); ?>
									</li>
									<li>
										<?php esc_html_e('Use single straight quotes (instead of double quotes) in', 'usp'); ?> 
										<code>before</code> <?php esc_html_e('and', 'usp'); ?> <code>after</code> <?php esc_html_e('attributes', 'usp'); ?>
									</li>
									<li><?php esc_html_e('Can use', 'usp'); ?> <code>%%url%%</code> <?php esc_html_e('to get the URL of the full-size image', 'usp'); ?></li>
									<li><?php esc_html_e('Check out the source code inline notes for more info', 'usp'); ?></li>
								</ul>
							</div>
							
							<h3><?php esc_html_e('Reset Form Button', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('This shortcode displays a link that resets the form to its original state:', 'usp'); ?></p>
								<pre>[usp-reset-button]</pre>
								<p><?php esc_html_e('This shortcode accepts the following attributes:', 'usp'); ?></p>
<pre>class  = classes for the parent element (optional, default: none)
value  = link text (optional, default: "Reset form")
url    = the URL where your form is displayed (required, default: none)</pre>
								<p><?php esc_html_e('Note that the url attribute accepts', 'usp'); ?> <code>%%current%%</code> <?php esc_html_e('to get the current URL.', 'usp'); ?></p>
							</div>
							
							<h3><?php esc_html_e('Access Control', 'usp'); ?></h3>
							<div class="shortcode-info">
								<p><?php esc_html_e('USP provides three shortcodes to control access and restrict content.', 'usp'); ?></p>
								<p><?php esc_html_e('Display content only to users with a specific capability:', 'usp'); ?></p>
								<pre>[usp_access cap="read" deny="Message for users without read capability"][/usp_access]</pre>
								<p><?php esc_html_e('Display content to logged-in users:', 'usp'); ?></p>
								<pre>[usp_member deny="Message for users who are not logged in"][/usp_member]</pre>
								<p><?php esc_html_e('Display content to visitors only:', 'usp'); ?></p>
								<pre>[usp_visitor deny="Message for users who are logged in"][/usp_visitor]</pre>
								<p>
									<strong><?php esc_html_e('Tip:', 'usp'); ?></strong> 
									<?php esc_html_e('to include markup in the deny message, you can use', 'usp'); ?> <code>{tag}</code> <?php esc_html_e('to output', 'usp'); ?> <code>&lt;tag&gt;</code>.
								</p>
								<p><strong><?php esc_html_e('Example', 'usp'); ?></strong></p>
								<p><?php esc_html_e('If the user is logged in, display the post-submit form; or if the user is not logged in, display the login form:', 'usp'); ?></p>
<pre>[usp_member]
[user-submitted-posts]
[/usp_member]					

[usp_visitor]
[usp-login-form]
[/usp_visitor]</pre>
								<p>
									<?php esc_html_e('The access shortcodes can be added to any WP Post or Page. So you can display forms and other content conditionally, based on user role and login status.', 'usp'); ?>
								</p>
								<p class="no-bottom-margin">
									<strong><?php esc_html_e('Tip:', 'usp'); ?></strong> 
									<?php esc_html_e('The pro version provides more flexibility with access-control shortcodes.', 'usp'); ?> 
									<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro-nested-shortcodes/"><?php esc_html_e('Learn&nbsp;more', 'usp'); ?>&nbsp;&raquo;</a>
								</p>
							</div>
							
						</div>
						
					</div>
					
					<div id="mm-restore-settings" class="postbox">
						<h2><?php esc_html_e('Restore Defaults', 'usp'); ?></h2>
						<div class="toggle default-hidden">
							<p class="first-child"><?php esc_html_e('Leave this option disabled to remember your settings.', 'usp'); ?></p>
							<p><?php esc_html_e('Or, to go ahead and restore the default plugin options: check the box, save your settings, and then deactivate/reactivate the plugin.', 'usp'); ?></p>
							<p>
								<input name="usp_options[default_options]" type="checkbox" value="1" id="mm_restore_defaults" <?php if (isset($usp_options['default_options'])) checked('1', $usp_options['default_options']); ?> /> 
								<label class="description" for="usp_options[default_options]"><?php esc_html_e('Restore default options upon plugin deactivation/reactivation', 'usp'); ?></label>
							</p>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'usp'); ?>" />
						</div>
					</div>
					
					<div id="mm-panel-current" class="postbox">
						<h2><?php esc_html_e('WP Resources', 'usp'); ?></h2>
						<div class="toggle<?php if (isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
							<?php require_once('support-panel.php'); user_submitted_posts_wp_resources(); ?>
						</div>
					</div>
				</div>
			</div>
			
			<div id="mm-credit-info">
				<a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/user-submitted-posts/" title="<?php esc_attr_e('Plugin Homepage', 'usp'); ?>"><?php echo USP_PLUGIN; ?></a> <?php esc_html_e('by', 'usp'); ?> 
				<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable" title="<?php esc_attr_e('Jeff Starr on Twitter', 'usp'); ?>">Jeff Starr</a> @ 
				<a target="_blank" rel="noopener noreferrer" href="https://monzillamedia.com/" title="<?php esc_attr_e('Obsessive Web Design &amp; Development', 'usp'); ?>">Monzilla Media</a>
			</div>
		</form>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			
			// dismiss alert
			if (!$('.dismiss-alert-wrap input').is(':checked')){
				$('.dismiss-alert-wrap input').one('click', function(){
					$('.dismiss-alert-wrap').after('<input type="submit" class="button-secondary" value="<?php esc_attr_e('Save Preference', 'usp'); ?>" />');
				});
			}
			
		});
	</script>
	
<?php 

}