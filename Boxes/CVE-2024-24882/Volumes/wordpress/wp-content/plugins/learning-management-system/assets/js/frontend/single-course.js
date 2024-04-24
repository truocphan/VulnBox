(function ($, mto_data) {
	var masteriyo_api = {
		deleteCourseReview: function (id, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews/' + id;

			if (options.force_delete) {
				url += '?force_delete=true';
			} else {
				url += '?force_delete=false';
			}

			$.ajax({
				type: 'delete',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
		createCourseReview: function (data, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews';
			$.ajax({
				type: 'post',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				data: data,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
		updateCourseReview: function (id, data, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews/' + id;
			$.ajax({
				type: 'put',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				data: data,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
		getCourseReviewsPageHtml: function (data, options) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'masteriyo_course_reviews_infinite_loading',
					nonce: mto_data.reviews_listing_nonce,
					page: data.page,
					course_id: mto_data.course_id,
				},
				url: mto_data.ajaxURL,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
	};
	var masteriyo_utils = {
		getErrorNotice: function (message) {
			return (
				'<div class="masteriyo-notify-message masteriyo-alert masteriyo-danger-msg"><span>' +
				message +
				'</span></div>'
			);
		},
		getSuccessNotice: function (message) {
			return (
				'<div class="masteriyo-notify-message masteriyo-alert masteriyo-success-msg"><span>' +
				message +
				'</span></div>'
			);
		},
	};
	var masteriyo_helper = {
		confirm: function () {
			var res = window.prompt(mto_data.labels.type_confirm);

			if (null === res) return false;
			if ('CONFIRM' !== res) {
				alert(mto_data.labels.try_again);
				return false;
			}
			return true;
		},
		removeNotices: function ($element) {
			$element.find('.masteriyo-notify-message').remove();
		},
		get_rating_markup: function (rating) {
			rating = rating === '' ? 0 : rating;
			rating = parseFloat(rating);
			html = '';
			max_rating = mto_data.max_course_rating;
			rating = rating > max_rating ? max_rating : rating;
			rating = rating < 0 ? 0 : rating;
			stars = mto_data.rating_indicator_markup;

			rating_floor = Math.floor(rating);
			for (i = 1; i <= rating_floor; i++) {
				html += stars.full_star;
			}
			if (rating_floor < rating) {
				html += stars.half_star;
			}

			rating_ceil = Math.ceil(rating);
			for (i = rating_ceil; i < max_rating; i++) {
				html += stars.empty_star;
			}
			return html;
		},
	};
	var masteriyo_dialogs = {
		confirm_delete_course_review: function (options = {}) {
			$(document.body).append(
				$('.masteriyo-confirm-delete-course-review-modal-content').html()
			);
			$('.masteriyo-modal-confirm-delete-course-review .masteriyo-cancel').on(
				'click',
				function () {
					$(this).closest('.masteriyo-overlay').remove();
				}
			);
			$('.masteriyo-modal-confirm-delete-course-review .masteriyo-delete').on(
				'click',
				function () {
					var $modal = $(this).closest('.masteriyo-overlay');

					$modal.find('.masteriyo-cancel').attr('disabled', true);
					$(this).text(mto_data.labels.deleting);

					if (typeof options.onConfirm === 'function') {
						options.onConfirm(function () {
							$modal.remove();
						});
					}
				}
			);
		},
	};
	var masteriyo = {
		$create_review_form: $('.masteriyo-submit-review-form'),
		create_review_form_class: '.masteriyo-submit-review-form',

		init: function () {
			$(document).ready(function () {
				masteriyo.init_sticky_sidebar();
				masteriyo.init_rating_widget();
				masteriyo.init_course_reviews_menu();
				masteriyo.init_curriculum_accordions_handler();
				masteriyo.init_create_reviews_handler();
				masteriyo.init_edit_reviews_handler();
				masteriyo.init_delete_reviews_handler();
				masteriyo.init_reply_btn_handler();
				masteriyo.init_course_reviews_loader();
			});
		},
		init_course_reviews_menu: function () {
			/**
			 * Menu toggle handler.
			 */
			$(document.body).on('click', '.menu-toggler', function () {
				if ($(this).siblings('.menu').height() == 0) {
					$(this).siblings('.menu').height('auto');
					$(this).siblings('.menu').css('max-height', '999px');
					return;
				}
				$(this).siblings('.menu').height(0);
			});

			/**
			 * Close menu on click menu item.
			 */
			$('.masteriyo-dropdown .menu li').on('click', function () {
				$(this).closest('.menu').height(0);
			});

			/**
			 * Close menu on outside click.
			 */
			$(document.body).click(function (e) {
				if ($('.masteriyo-dropdown').has(e.target).length > 0) {
					return;
				}
				$('.masteriyo-dropdown .menu').height(0);
			});
		},
		init_rating_widget: function () {
			function addClickListener($starsParent) {
				$starsParent
					.find('.masteriyo-rating-input-icon')
					.on('click', function () {
						var rating = $(this).index() + 1;

						masteriyo.$create_review_form
							.find('input[name="rating"]')
							.val(rating);
						$(this)
							.closest('.masteriyo-rstar')
							.html(masteriyo_helper.get_rating_markup(rating));
					});
			}

			var isDoneReset = false;
			var lastHoveredRating = null;

			masteriyo.$create_review_form.on('mouseover', function (e) {
				var $star = $(e.target).closest('.masteriyo-rating-input-icon');

				if ($star.length === 0) {
					if (isDoneReset) {
						return;
					}
					var rating = masteriyo.$create_review_form
						.find('input[name="rating"]')
						.val();

					masteriyo.$create_review_form
						.find('.masteriyo-rstar')
						.html(masteriyo_helper.get_rating_markup(rating));

					isDoneReset = true;
					lastHoveredRating = null;

					return;
				}
				var rating = $star.index() + 1;
				var $starsParent = $star.closest('.masteriyo-rstar');

				isDoneReset = false;

				if (lastHoveredRating === rating) {
					return;
				}
				lastHoveredRating = rating;

				$starsParent.html(masteriyo_helper.get_rating_markup(rating));
				addClickListener($starsParent);
			});
		},
		init_create_reviews_handler: function () {
			var isCreating = false;

			masteriyo.$create_review_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_review_form;
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					rating: $form.find('input[name="rating"]').val(),
					content: $form.find('[name="content"]').val(),
					parent: $form.find('[name="parent"]').val(),
					course_id: $form.find('[name="course_id"]').val(),
				};

				if (isCreating || 'yes' === $form.data('edit-mode')) return;

				isCreating = true;
				$submit_button.text(mto_data.labels.submitting);
				masteriyo_helper.removeNotices($form);
				masteriyo_api.createCourseReview(data, {
					onSuccess: function () {
						$form.append(
							masteriyo_utils.getSuccessNotice(mto_data.labels.submit_success)
						);
						$form.trigger('reset');
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text(mto_data.labels.submit);
					},
					onComplete: function () {
						isCreating = false;
					},
				});
			});
		},
		init_reply_btn_handler: function () {
			$(document.body).on(
				'click',
				'.masteriyo-reply-course-review',
				function (e) {
					e.preventDefault();

					var $form = masteriyo.$create_review_form;
					var $review = $(this).closest('.masteriyo-course-review');
					var review_id = $review.data('id');
					var $submit_button = $form.find('button[type="submit"]');
					var title = $review.find('.title').data('value');

					$form.find('input[name="title"]').val('');
					$form.find('input[name="rating"]').val(0);
					$form
						.find('.masteriyo-rstar')
						.html(masteriyo_helper.get_rating_markup(0));
					$form.find('[name="content"]').val('');
					$form.find('[name="parent"]').val(review_id);
					$submit_button.text(mto_data.labels.submit);

					$('.masteriyo-form-title').text(
						mto_data.labels.reply_to + ': ' + title
					);
					$form.find('.masteriyo-title, .masteriyo-rating').hide();
					$form.find('[name="content"]').focus();
					$('html, body').animate(
						{
							scrollTop: $form.offset().top,
						},
						500
					);
				}
			);
		},
		init_edit_reviews_handler: function () {
			$(document.body).on(
				'click',
				'.masteriyo-edit-course-review',
				function (e) {
					e.preventDefault();

					var $form = masteriyo.$create_review_form;
					var $review = $(this).closest('.masteriyo-course-review');
					var review_id = $review.data('id');
					var $submit_button = $form.find('button[type="submit"]');
					var title = $review.find('.title').data('value');
					var rating = $review.find('.rating').data('value');
					var content = $review.find('.content').data('value');
					var parent = $review.find('[name="parent"]').val();

					$form.data('edit-mode', 'yes');
					$form.data('review-id', review_id);
					$form.find('input[name="title"]').val(title);
					$form.find('input[name="rating"]').val(rating);
					$form
						.find('.masteriyo-rstar')
						.html(masteriyo_helper.get_rating_markup(rating));
					$form.find('[name="content"]').val(content);
					$form.find('[name="parent"]').val(parent);
					$submit_button.text(mto_data.labels.update);

					if ($review.is('.is-course-review-reply')) {
						$('.masteriyo-form-title').text(mto_data.labels.edit_reply);
						$form.find('.masteriyo-title, .masteriyo-rating').hide();
						$form.find('[name="content"]').focus();
						$('html, body').animate(
							{
								scrollTop: $form.offset().top,
							},
							500
						);
					} else {
						$('.masteriyo-form-title').text(
							mto_data.labels.edit_review + ': ' + title
						);
						$form.find('.masteriyo-title, .masteriyo-rating').show();
						$form.find('input[name="title"]').focus();
						$('html, body').animate(
							{
								scrollTop: $form.offset().top,
							},
							500
						);
					}
				}
			);

			var isSubmitting = false;

			masteriyo.$create_review_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_review_form;
				var review_id = $form.data('review-id');
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					rating: $form.find('input[name="rating"]').val(),
					content: $form.find('[name="content"]').val(),
					parent: $form.find('[name="parent"]').val(),
					course_id: $form.find('[name="course_id"]').val(),
				};

				if (isSubmitting || 'yes' !== $form.data('edit-mode')) return;

				isSubmitting = true;
				$submit_button.text(mto_data.labels.submitting);
				masteriyo_helper.removeNotices($form);
				masteriyo_api.updateCourseReview(review_id, data, {
					onSuccess: function () {
						$form.append(
							masteriyo_utils.getSuccessNotice(mto_data.labels.update_success)
						);
						$submit_button.text(mto_data.labels.update);
						$form.trigger('reset');
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text(mto_data.labels.update);
					},
					onComplete: function () {
						isSubmitting = false;
					},
				});
			});
		},
		init_delete_reviews_handler: function () {
			var isDeletingFlags = {};

			$(document.body).on(
				'click',
				'.masteriyo-delete-course-review',
				function (e) {
					e.preventDefault();

					var $review = $(this).closest('.masteriyo-course-review');
					var $delete_button = $(this);
					var review_id = $review.data('id');
					var $replies = $('[name=parent][value=' + review_id + ']');

					if (isDeletingFlags[review_id]) return;

					masteriyo_dialogs.confirm_delete_course_review({
						onConfirm: function (closeModal) {
							isDeletingFlags[review_id] = true;

							masteriyo_api.deleteCourseReview(review_id, {
								force_delete: $replies.length === 0,

								onSuccess: function () {
									if ($review.hasClass('is-course-review-reply')) {
										var isDeleteReplyContainer =
											$review.siblings().length === 0;
										var $parentReview = $review
											.closest('.masteriyo-course-review-replies')
											.prev();

										if (
											isDeleteReplyContainer &&
											$parentReview.hasClass('masteriyo-delete-review-notice')
										) {
											$parentReview.fadeOut(500, function () {
												$(this).remove();
											});
										}

										$review.fadeOut(500, function () {
											if (isDeleteReplyContainer) {
												$review
													.closest('.masteriyo-course-review-replies')
													.remove();
											}
											$(this).remove();
										});
										return;
									}

									if (
										$review.next().hasClass('masteriyo-course-review-replies')
									) {
										$review.after(mto_data.review_deleted_notice);
									}
									$review.remove();
								},
								onError: function (xhr, status, error) {
									var message = error;

									if (xhr.responseJSON && xhr.responseJSON.message) {
										message = xhr.responseJSON.message;
									}

									$review.append(masteriyo_utils.getErrorNotice(message));
									$delete_button.find('.text').text(mto_data.labels.delete);
								},
								onComplete: function () {
									isDeletingFlags[review_id] = false;
									closeModal();
								},
							});
						},
					});
				}
			);
		},
		init_sticky_sidebar: function () {
			var $content_ref = $('.masteriyo-single-course--main').get(0);

			if ($content_ref) {
				$(window).scroll(function () {
					var scroll_position = $(window).scrollTop();
					var content_y = $content_ref.offsetTop;
					var content_y2 = content_y + $content_ref.offsetHeight;
					var isSticky = false;

					if (scroll_position > content_y && scroll_position < content_y2)
						isSticky = true;
					if (isSticky) {
						$('.masteriyo-single-course--aside').css({
							position: 'sticky',
							top: '7.5rem',
						});
					} else {
						$('.masteriyo-single-course--aside').css({
							position: 'relative',
							top: '0',
						});
					}
				});
			}
		},
		init_curriculum_accordions_handler: function () {
			// Curriculum Tab
			$(document.body).on('click', '.masteriyo-cheader', function () {
				$(this).parent('.masteriyo-stab--citems').toggleClass('active');
				if (
					$('.masteriyo-stab--citems').length ===
					$('.masteriyo-stab--citems.active').length
				) {
					expandAllSections();
				}
				if (
					$('.masteriyo-stab--citems').length ===
					$('.masteriyo-stab--citems').not('.active').length
				) {
					collapseAllSections();
				}
			});
			var isCollapsedAll = true;
			$(document.body).on(
				'click',
				'.masteriyo-expand-collapse-all',
				function () {
					if (isCollapsedAll) {
						expandAllSections();
					} else {
						collapseAllSections();
					}
				}
			);

			// Expand all
			function expandAllSections() {
				$('.masteriyo-stab--citems').addClass('active');
				$('.masteriyo-expand-collapse-all').text(mto_data.labels.collapse_all);
				isCollapsedAll = false;
			}

			// Collapse all
			function collapseAllSections() {
				$('.masteriyo-stab--citems').removeClass('active');
				$('.masteriyo-expand-collapse-all').text(mto_data.labels.expand_all);
				isCollapsedAll = true;
			}
		},
		init_course_reviews_loader: function () {
			var isLoadingReviews = false;
			var currentPage = 1;

			$('button.masteriyo-load-more').on('click', function () {
				if (isLoadingReviews) {
					return;
				}
				var $button = $(this);

				isLoadingReviews = true;
				$button.text(mto_data.labels.loading);

				masteriyo_api.getCourseReviewsPageHtml(
					{ page: currentPage + 1 },
					{
						onSuccess: function (res) {
							if (res.success) {
								currentPage += 1;

								if (currentPage >= mto_data.course_review_pages) {
									$button.remove();
								}
								$('.masteriyo-course-reviews-list').append(res.data.html);
								$('.course-reviews .masteriyo-danger-msg').remove();
							}
						},
						onError: function (xhr, status, error) {
							var message = error;

							if (
								xhr.responseJSON &&
								xhr.responseJSON.data &&
								xhr.responseJSON.data.message
							) {
								message = xhr.responseJSON.data.message;
							}

							if (!message) {
								message = mto_data.labels.see_more_reviews;
							}

							$button.after(masteriyo_utils.getErrorNotice(message));
						},
						onComplete: function () {
							isLoadingReviews = false;
							$button.text(mto_data.labels.see_more_reviews);
						},
					}
				);
			});
		},
	};

	masteriyo.init();
})(jQuery, window.masteriyo_data);

function masteriyo_select_single_course_page_tab(e, tabContentSelector) {
	jQuery(
		'.masteriyo-single-course--main__content .masteriyo-tab.active-tab'
	).removeClass('active-tab');
	jQuery('.masteriyo-single-course--main__content .tab-content').addClass(
		'masteriyo-hidden'
	);

	jQuery(e.target).addClass('active-tab');
	jQuery(
		'.masteriyo-single-course--main__content ' + tabContentSelector
	).removeClass('masteriyo-hidden');
}
