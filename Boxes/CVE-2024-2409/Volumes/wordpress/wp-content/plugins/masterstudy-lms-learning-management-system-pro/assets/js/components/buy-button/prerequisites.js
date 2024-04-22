(function ($) {
    $(document).ready(function () {
        $('.masterstudy-prerequisites__button').on('click', function (e) {
			e.preventDefault();
			$(this).parent().toggleClass('active');
		});

		$(document).on('click', function(event) {
			if (!$(event.target).closest('.masterstudy-prerequisites').length) {
				$('.masterstudy-prerequisites').removeClass('active');
			}
		});

		$('.masterstudy-prerequisites-list__explanation-title').on('click', function (e) {
			e.preventDefault();
			$(this).parent().toggleClass('active');
		});
    })
})(jQuery);