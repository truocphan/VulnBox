(function($){
	$(document).ready(function(){
		$('.masterstudy-points').on('click', function(e){
			e.preventDefault();

			var $this = $(this);

			if($this.hasClass('masterstudy-points-not-enough-points')) return false;

			if(!confirm(masterstudy_buy_button_points['translate']['confirm'])) return false;

			$.ajax({
				url: masterstudy_buy_button_points.ajax_url,
				dataType: 'json',
				context: this,
				data: {
					action: 'stm_lms_buy_for_points',
					course_id: masterstudy_buy_button_points.course_id,
					nonce: masterstudy_buy_button_points.get_nonce,
				},
				beforeSend: function () {
					$this.addClass('loading');
				},
				complete: function (data) {
					var data = data['responseJSON'];

					window.location.href = data.url;

					$this.removeClass('loading');
				}
			});

		});

		$('.masterstudy-points__icon').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var win = window.open($(this).data('href'), '_blank');
			win.focus();
		});
	});
})(jQuery);