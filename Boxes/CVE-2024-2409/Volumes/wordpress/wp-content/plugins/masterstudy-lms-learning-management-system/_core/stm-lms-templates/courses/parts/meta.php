<?php
/**
 * @var $lectures
 * @var $duration
 * @var $level
 */

$levels = STM_LMS_Helpers::get_course_levels();

if(!empty($levels[$level])): ?>
	<div class="stm_lms_course__meta">
		<i class="stmlms-level"></i>
		<?php echo wp_kses_post($levels[$level]); ?>
	</div>
<?php endif; ?>

<?php if(!empty($lectures['lessons'])): ?>
	<div class="stm_lms_course__meta">
		<i class="stmlms-cats"></i>
		<?php printf(esc_html__('%s Lectures', 'masterstudy-lms-learning-management-system'), $lectures['lessons']); ?>
	</div>
<?php endif; ?>

<?php if(!empty($duration)): ?>
	<div class="stm_lms_course__meta">
		<i class="stmlms-lms-clocks"></i>
		<?php echo wp_kses_post($duration); ?>
	</div>
<?php endif; ?>
