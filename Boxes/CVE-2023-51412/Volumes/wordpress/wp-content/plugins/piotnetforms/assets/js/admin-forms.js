jQuery(document).ready(function( $ ) {
	$(window).load(function() {
		var $toolbar = $(document).find('#titlediv');
		$toolbar.append('<button class="button button-primary button-large" data-edit-with-piotnetforms>Edit With Piotnet Forms</button>');
	});

	$(document).on('click', '[data-edit-with-piotnetforms]', function(e){
		e.preventDefault();
		var post_id = $('#post_ID').val(),
			post_title = $('[name="post_title"]').val(),
			status = $('#original_post_status').val(),
			admin_url = $('[data-piotnetforms-admin-url]').attr('data-piotnetforms-admin-url');
        $('#submitpost [type="submit"]').trigger('click');

		if (status === 'auto-draft') {
			var data = {
				post_id: post_id,
				post_title: post_title,
				action: 'piotnetforms_save_draft',
			};

			$.post(ajaxurl, data, function (response) { 
				window.location.href = admin_url + 'admin.php?page=piotnetforms&post=' + post_id;
			});
		} else {
			window.location.href = admin_url + 'admin.php?page=piotnetforms&post=' + post_id;
		}

	});
});