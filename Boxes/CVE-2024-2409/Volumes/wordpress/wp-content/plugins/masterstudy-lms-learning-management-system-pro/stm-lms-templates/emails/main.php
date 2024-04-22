<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName.
/**
 * Email template
 *
 * @var $subject
 * @var $message
 * @var $email_manager
 */

$header_bg            = ! empty( $email_manager['stm_lms_email_template_hf_header_bg'] ) ? STM_LMS_Email_Manager::stm_lms_get_image_by_id( $email_manager['stm_lms_email_template_hf_header_bg'] ) ?? '' : STM_LMS_PRO_URL . '/addons/email_manager/email_header.png';
$logo                 = ! empty( $email_manager['stm_lms_email_template_hf_logo'] ) ? STM_LMS_Email_Manager::stm_lms_get_image_by_id( $email_manager['stm_lms_email_template_hf_logo'] ) ?? '' : STM_LMS_PRO_URL . '/addons/email_manager/email_logo.png';
$footer_bg            = ! empty( $email_manager['stm_lms_email_template_hf_footer_bg'] ) ? STM_LMS_Email_Manager::stm_lms_get_image_by_id( $email_manager['stm_lms_email_template_hf_footer_bg'] ) ?? '' : STM_LMS_PRO_URL . '/addons/email_manager/email_footer.png';
$reply_icon           = ! empty( $email_manager['stm_lms_email_template_reply_icon'] ) ? STM_LMS_Email_Manager::stm_lms_get_image_by_id( $email_manager['stm_lms_email_template_reply_icon'] ) ?? '' : STM_LMS_PRO_URL . '/addons/email_manager/email_reply.png';
$footer_copyrights    = $email_manager['stm_lms_email_template_reply_textarea'] ?? '';
$footer_reply         = $email_manager['stm_lms_email_template_reply_text'] ?? '';
$outside_bg           = $email_manager['stm_lms_email_template_hf_entire_bg'] ?? '';
$status_header_footer = $email_manager['stm_lms_email_template_hf'] ?? '';
$status_reply         = $email_manager['stm_lms_email_template_reply'] ?? '';

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Facebook sharing information tags -->
<meta property="og:title" content="Masterstudy LMS Email Template">

<title>Masterstudy LMS Email Template</title>
<center style="margin: 0;padding: 0;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; font-style: normal;font-weight: 500;font-size: 15px;line-height: 26px;color: #808C98;background-color: <?php echo ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ? esc_html( $outside_bg ) : 'white'; ?>;">
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;height: 100% !important;width: 100% !important;">
		<tbody>
			<tr>
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" style="border: 1px solid <?php echo ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ? '#DDDDDD' : 'white'; ?>; margin-top: 40px;background-color: #FFFFFF;">
						<tbody>
						<tr>
							<td align="center" valign="top">
								<!-- // Begin Template Header \\ -->
								<?php
								if ( $email_manager['stm_lms_email_template_branding'] ) {
									?>
									<table border="0" cellpadding="0" cellspacing="0" id="templateHeader" style="border-bottom: 0;">
										<tbody>
										<tr
										<?php if ( ! empty( $header_bg ) && ! empty( $status_header_footer ) ) { ?>
											style="background-image: url(<?php echo esc_attr( $header_bg ); ?>); background-repeat: no-repeat; background-size: cover;"
										<?php } ?>
										>
											<td class="headerContent" style="text-align:center;">
												<div style="max-width:700px;object-fit:cover;height:95px;width:700px;line-height:95px;" id="headerImage campaign-icon">
												<?php
												if ( ! empty( $logo ) && ! empty( $status_header_footer ) ) {
													?>
													<img src="<?php echo esc_attr( $logo ); ?>" style="max-width:700px;width:200px;height:35px;object-fit:contain;display: inline-block; vertical-align: middle;" id="headerImage campaign-icon" mc:label="header_image" mc:edit="header_image" mc:allowdesigner="" mc:allowtext="" alt="" class="email-logo">
													<?php
												}
												?>
												</div>
											</td>
										</tr>
										<tr>
											<td class="headerContent-bottom" style="width: 620px;height: 1px;background-color: <?php echo ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ? '#DBE0E9' : 'white'; ?>;display: block;margin: 0 auto;margin-bottom: 50px;">
											</td>
										</tr>
										</tbody>
									</table>
									<?php
								}
								?>
								<!-- // End Template Header \\ -->
							</td>
						</tr>
						<tr class="columnOneContent courseTitle">
							<td>
								<h2 style="color: black;text-align: center;margin-bottom: 30px;"><?php echo esc_html( $subject ); ?></h2>
							</td>
						</tr>
						<tr class="columnOneContent courseBody">
							<td>
								<div class="courseContentBody" style="max-width: 460px;margin: 0 auto;text-align: center;margin-bottom: 30px !important;">
								<?php echo $message; // phpcs:ignore?>
								</div>
							</td>
						</tr>
						<tr style="background-color: white;">
							<td class="headerContent-bottom no-margin" style="width: 620px;height: 1px;background-color: <?php echo ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ? '#DBE0E9' : 'white'; ?>;display: block;margin: 0 auto;margin-bottom: 0px;">
							</td>
						</tr>
						<?php
						if ( ! empty( $footer_bg ) && ! empty( $status_header_footer ) && ! empty( $email_manager['stm_lms_email_template_branding'] ) ) {
							?>
						<tr class="columnOneContent courseFooter copyrights" style="background-image: url(<?php echo esc_attr( $footer_bg ); ?>);background-repeat: no-repeat;background-size: cover;max-width: 700px;object-fit: cover;height: 155px;width: 700px;position: relative;">
							<?php
						} else {
							?>
						</tr><tr class="columnOneContent courseFooter copyrights" style="max-width: 700px;object-fit: cover;height: 155px;width: 700px;position: relative;">
							<?php
						}
						if ( ! empty( $status_reply ) ) {
							?>

						<td class="copyright-content">
							<p class="reply-email-link" style="margin-bottom:30px;text-align:center;vertical-align:middle;line-height:18px;">
								<?php
								if ( ! empty( $reply_icon ) && ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ) {
									?>
									<img src="<?php echo esc_attr( $reply_icon ); ?>" class="courseFooterIcon" style="margin-right:3px;width:18px;display:inline-block;vertical-align:middle;">
									<?php
								}
								?>
								<span style="display:inline-block; vertical-align: middle;">
								<?php
								if ( ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ) {
									echo esc_html( $footer_reply );
								}
								?>
								</span>
							</p>

							<?php
							if ( ! empty( $footer_copyrights ) && ( ! empty( $email_manager['stm_lms_email_template_branding'] ) ) ) {
								?>
								<div class="content">
									<p style="margin-bottom: 30px;text-align: center;">
									<?php echo $footer_copyrights; // phpcs:ignore ?>
									</p>
								</div>
								<?php
							}
							?>
						</td>
							<?php
						} elseif ( ! empty( $email_manager['stm_lms_email_template_branding'] ) && empty( $footer_bg ) ) {
							?>
							</tr><tr class="columnOneContent courseFooter copyrights" style="max-width: 700px;object-fit: cover;height: 100px;width: 700px;position: relative;">
								<td>
									<p class="reply-email-link" style="display: flex;align-items: center;justify-content: center;margin-bottom: 30px;text-align: center;"></p>
								</td>
								<?php
						}
						?>
							<td></td>
							</tr>
						</tbody>
					</table>
					<!-- // End Template Body \\ -->
				</td>
			</tr>
		</tbody>
	</table>
	<br>
</center>
