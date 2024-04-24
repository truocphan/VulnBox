/**
 * Highlight sidebar sub menu during page load and when the menu is clicked.
 */
(function ($) {
	var $topLevelMenu = $('#toplevel_page_masteriyo');

	if (!$topLevelMenu.length) {
		return;
	}

	function makeCurrentSubmenuActive() {
		var hash = window.location.hash;

		// Fix for when user goes to instructors tab and reloads.
		if ('#/users/instructors' === hash) {
			hash = '#/users/students';
		}

		// Fix for when user goes to course difficulties tab and reloads.
		if ('#/courses/difficulties' === hash) {
			hash = '#/courses/categories';
		}

		$topLevelMenu.find('li').removeClass('current');
		$topLevelMenu
			.find('a[href$="' + hash + '"]')
			.parent('li')
			.addClass('current');
	}

	makeCurrentSubmenuActive();

	// Handle when URL changes.
	window.addEventListener('popstate', () => {
		if ('#/courses' === window.location.hash) {
			makeCurrentSubmenuActive();
		}
	});

	// Handle when user clicks on admin menu.
	$topLevelMenu.on('click', '.wp-submenu li', function (e) {
		$topLevelMenu.find('li').removeClass('current');
		$(this).addClass('current');
	});
})(jQuery);
