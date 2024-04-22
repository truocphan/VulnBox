<script>
	<?php
	ob_start();
	require STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/components/manage_udemy_posts.php';
	$template = preg_replace( "/\r|\n/", '', addslashes( ob_get_clean() ) );
	?>

	Vue.component('stm-manage-post-type', {
		props: ['post_type', 'meta_key'],
		data: function () {
			return {
				posts: [],
				pages: [],
				current_page: 1,
				total: 0,
				filter: '',
				loading: false,
				updating_all: false,
				updatingIndex: 0
			}
		},
		mounted: function () {
			var _this = this;
			_this.getPosts();

			WPCFTO_EventBus.$on('stm_lms_udemy_course_imported', function () {
				_this.getPosts();
			});
		},
		template: '<?php echo stm_wpcfto_filtered_output( $template ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>',
		methods: {
			getPosts() {
				var _this = this;
				var url = stm_wpcfto_ajaxurl + '?action=stm_manage_posts&nonce=' + stm_lms_nonces['stm_manage_posts'];
				url += '&post_types=' + _this.post_type;
				url += '&page=' + _this.current_page;
				url += '&post_status=' + _this.filter;
				url += '&meta=' + _this.meta_key;
				_this.loading = true;

				_this.$http.get(url).then(function (r) {
					_this.posts = r.body['posts'];
					_this.total = Math.ceil(r.body.total / r.body.per_page);
					_this.pagination();
					_this.loading = false;

					if (_this.updating_all) _this.updatingIndex = 0;

				})
			},
			pagination() {
				this.pages = [];
				var i = 0;
				while (i < this.total) {
					i++;
					this.pages.push(i);
				}
			},
			switchPage(page) {
				this.current_page = page;
				this.getPosts();
			},
			switchStatus() {
				this.current_page = 1;
				this.getPosts();
			},
			updateCourse(post) {
				var _this = this;
				_this.$set(post, 'loading', true);
				_this.$set(post, 'loading_text', 'Updating Course');


				_this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_lms_pro_udemy_import_courses&nonce=' + stm_lms_pro_nonces['stm_lms_pro_udemy_import_courses'] + '&id=' + post.id + '&update=true').then(function (r) {
					var r = r.body;

					_this.$set(post, 'loading_text', r.message);

					_this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_lms_pro_udemy_import_curriculum&nonce=' + stm_lms_pro_nonces['stm_lms_pro_udemy_import_curriculum'] + '&id=' + r.course_id + '&update=true').then(function (r) {
						var r = r.body;

						_this.$set(post, 'loading', false);
						_this.$set(post, 'loading_text', 'Updated!');

						if (_this.updating_all) _this.updatingIndex++;
					})
				});

			},
			changeStatus(index, post_id, status) {
				var _this = this;

				_this.$set(_this.posts[index], 'loading', true);

				_this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_lms_change_post_status&nonce=' + stm_lms_pro_nonces['stm_lms_change_post_status'] + '&post_id=' + post_id + '&status=' + status).then(function (r) {
					r = r.body;

					_this.$set(_this.posts[index], 'loading', false);
					_this.$set(_this.posts[index], 'status', r);
				});
			},
			updateAll() {
				var _this = this;

				if (_this.updating_all) {
					if (typeof this.posts[_this.updatingIndex] !== 'undefined') {
						_this.updateCourse(this.posts[_this.updatingIndex]);
					} else {
						if (_this.pages.length > _this.current_page) {
							_this.switchPage(_this.current_page + 1);
						} else {
							_this.updating_all = false;
						}
					}
				}
			}
		},
		watch: {
			updatingIndex: function () {
				this.updateAll();
			}
		}
	})
</script>
