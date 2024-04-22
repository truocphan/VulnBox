<?php
stm_lms_register_style('announcement');
$announcement = get_post_meta(get_the_ID(), 'announcement', true); ?>

<div class="stm_lms_announcement">
	<?php if (!empty($announcement)): ?>
		<?php echo htmlspecialchars_decode($announcement); ?>
	<?php else: ?>
		<?php esc_html_e('No announcements at this moment.', 'masterstudy-lms-learning-management-system'); ?>
	<?php endif; ?>
</div>