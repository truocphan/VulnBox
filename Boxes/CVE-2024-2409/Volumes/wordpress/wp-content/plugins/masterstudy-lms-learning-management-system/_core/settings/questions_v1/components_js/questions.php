<script>

	Vue.component('vue-editor', Vue2Editor.default.VueEditor);

	<?php
	ob_start();
	include STM_WPCFTO_PATH .'/metaboxes/components/questions.php';
	$template = preg_replace("/\r|\n/", "", addslashes(ob_get_clean()));
	?>

	Vue.component('v-select', VueSelect.VueSelect);
	Vue.component('stm-questions', {
		props: ['posts', 'stored_ids'],
		data: function () {
			return {
				items: [],
				add_new_question: '',
				loading: false,
				search: '',
				searching: false,
				searchTimeout: '',
				options: [],
				question_type: 'single_choice',
				timer: '',
				timeout: '',
				isEmpty: ''
			}
		},
		template: `<?php echo stm_wpcfto_filtered_output($template); ?>`,
		mounted: function () {
			var _this = this;
			this.initComponent();

			if (typeof WPCFTO_EventBus !== 'undefined') {
				WPCFTO_EventBus.$on('STM_LMS_Questions_Update', function (item) {
					_this.stored_ids = item;
					_this.initComponent();
				});
			}
		},
		created: function () {

		},
		methods: {
			initComponent() {
				if (this.stored_ids) {
					this.getPosts(stm_wpcfto_ajaxurl + '?action=wpcfto_search_posts&nonce=' + stm_wpcfto_nonces['wpcfto_search_posts'] + '&posts_per_page=-1&orderby=post__in&ids=' + this.stored_ids + '&post_types=' + this.posts.join(','), 'items');
				} else {
					this.isLoading(false);
				}
			},
			createQuestion() {
				var vm = this;
				vm.isEmpty = false;
				clearTimeout(vm.timeout);
				if (vm.add_new_question === '') {

					vm.isEmpty = true;
					vm.timeout = setTimeout(function () {
						vm.isEmpty = false;
					}, 500);

					return false;
				}
				vm.isLoading(true);

				var url = stm_wpcfto_ajaxurl + '?action=stm_curriculum_create_item&nonce='+ stm_lms_nonces['stm_curriculum_create_item'] + '&post_type=' + this.posts.join(',') + '&title=' + vm.add_new_question;
				this.$http.get(url).then(function (response) {
					this.items.push(response.body);
					vm.add_new_question = '';
					vm.isLoading(false);
				});
			},
			changeTitle(post_id, title, itemKey) {
				if (isNaN(post_id)) {
					this.items[itemKey]['id'] = title;
					this.updateIds();
				} else {
					this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_save_title&nonce=' + stm_lms_nonces['stm_save_title'] + '&title=' + title + '&id=' + post_id);
				}
			},
			confirmDelete(item_key, message) {
				var r = confirm(message);
				if (!r) return;
				this.items.splice(item_key, 1);
			},
			onSearch(search) {
				var vm = this;

				clearTimeout(vm.searchTimeout);

				vm.searchTimeout = setTimeout(function () {
					var url = stm_wpcfto_ajaxurl + '?action=wpcfto_search_posts&nonce=' + stm_wpcfto_nonces['wpcfto_search_posts'] + '&s=' + search + '&post_types=' + vm.posts.join(',');
					vm.searching = true;
					vm.$http.get(url).then(function (response) {
						vm['options'] = response.body;
						vm.searching = false;
					});
				}, 1000);

			},
			getPosts(url, variable) {
				var vm = this;
				vm.isLoading(true);
				this.$http.get(url).then(function (response) {
					vm[variable] = response.body;
					vm.isLoading(false);
				});
			},
			isLoading(isLoading) {
				this.loading = isLoading;
			},
			containsObject(obj, list) {
				var i;
				for (i = 0; i < list.length; i++) {
					if (list[i]['id'] === obj['id']) {
						return true;
					}
				}

				return false;
			},
			updateIds() {
				var vm = this;
				vm.ids = [];
				this.items.forEach(function (value, key) {
					vm.ids.push(value.id);
				});
				vm.$emit('get-questions', vm.ids);
			},
			openQuestion(key) {
				if (typeof this.items[key]['opened'] === 'undefined') {
					this.$set(this.items[key], 'opened', true);
				} else {
					this.$set(this.items[key], 'opened', !this.items[key]['opened']);
				}
			},
			saveQuestions() {
				var vm = this;

				var $ = jQuery;
				var $publish_button = $('#publishing-action input[name="save"]');

				$publish_button.attr('disabled', 1);

				this.$http.post(stm_wpcfto_ajaxurl + '?action=stm_save_questions&nonce=' + stm_lms_nonces['stm_save_questions'], JSON.stringify(vm.items)).then(function () {
					$publish_button.removeAttr('disabled');
				}, function () {
					$publish_button.removeAttr('disabled');
				});
			}
		},
		watch: {
			search: function (value) {
				if (typeof value === 'object' && value !== null && !this.containsObject(value, this.items)) {
					this.items.push(value);
				}
			},
			items: {
				handler: function () {
					var vm = this;

					vm.updateIds();

					clearTimeout(vm.timer);
					vm.timer = setTimeout(function () {
						vm.saveQuestions();
					}, 500);
				},
				deep: true
			}
		}
	})
</script>