<?php
/**
 * @var $q
 * @var $item_id
 */

$duration = STM_LMS_Quiz::get_quiz_duration($item_id) * 1000;

wp_add_inline_script('stm-lms-quiz',
    "var stm_lms_quiz_duration = '{$duration}'"
);
?>

<div class="stm_lms_timer"
	 data-text-days="<?php esc_html_e('Days', 'masterstudy-lms-learning-management-system'); ?>"
	 data-text-hours="<?php esc_html_e('Hours', 'masterstudy-lms-learning-management-system'); ?>">
	<div class="stm_lms_timer__icon">
        <div class="stm_lms_timer__icon_arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="21.969" height="21.968" viewBox="0 0 21.969 21.968">
                <path d="M281.486,756.831a4.028,4.028,0,1,1,5.633,5.62L266.78,777.165Z" transform="translate(-266.781 -755.188)"/>
            </svg>
        </div>

        <svg width="130" height="130" class="stm_lms_timer__icon_timered">
            <circle r="31" cx="75" cy="75" stroke="#fff"></circle>
        </svg>

	</div>
	<div class="stm_lms_timer__time heading_font">
		<div class="stm_lms_timer__time_h"></div>
		<div class="stm_lms_timer__time_m"></div>
	</div>
	<div class="stm_lms_timer__answered">
		<?php printf(__('answered <strong>%s</strong>/<label>%s</label>', 'masterstudy-lms-learning-management-system'), 0, $q->found_posts); ?>
	</div>
</div>