var trigger_types_with_category = [
	'masteriyo_gamipress_complete_quiz_course_category',
	'masteriyo_gamipress_pass_quiz_course_category',
	'masteriyo_gamipress_fail_quiz_course_category',
	'masteriyo_gamipress_complete_lesson_course_category',
	'masteriyo_gamipress_complete_course_category',
	'masteriyo_gamipress_enroll_course_category',
];

(function ($) {
	$(function () {
		// Loop requirement list items to show/hide amount input on initial load.
		$('.requirements-list li').each(function () {
			var trigger_type = $(this).find('.select-trigger-type').val();
			var $masteriyo_category = $(this).find('.select-masteriyo-category');

			if (trigger_types_with_category.includes(trigger_type)) {
				$masteriyo_category.show();
			} else {
				$masteriyo_category.hide();
			}
		});
	});

	// Listen for change in trigger type.
	$(document.body).on(
		'change',
		'.requirements-list .select-trigger-type',
		function () {
			var trigger_type = $(this).val();
			var $masteriyo_category = $(this).siblings('.select-masteriyo-category');

			if (trigger_types_with_category.includes(trigger_type)) {
				$masteriyo_category.show();
			} else {
				$masteriyo_category.hide();
			}
		}
	);

	$(document.body).on(
		'update_requirement_data',
		'.requirements-list .requirement-row',
		function (e, requirement_details, requirement) {
			if (
				trigger_types_with_category.includes(requirement_details.trigger_type)
			) {
				requirement_details.masteriyo_category = requirement
					.find('.select-masteriyo-category')
					.val();
			}
		}
	);
})(jQuery);
