<script>
	Vue.component('v-select', VueSelect.VueSelect);
	Vue.component('stm-autocomplete-drip-content', {
		props: ['posts', 'stored_ids', 'label'],
		data: function () {
			return {
				options: [],
				items: [],
			}
		},
		created: function () {
			this.isLoading(false);
			if (this.stored_ids) {
				this.items = JSON.parse(this.stored_ids);
			}
		},
		methods: {
			isLoading(isLoading) {
				this.loading = isLoading;
			},
			onSearch($event, exclude_items) {
				var _this = this;
				var post_types = _this.posts.join(',');
				var exclude = exclude_items.map(a => a.id).join(',');
				var url = stm_wpcfto_ajaxurl + '?action=wpcfto_search_posts&nonce=' + stm_wpcfto_nonces['wpcfto_search_posts'] + '&s=' + $event + '&post_types=' + post_types;
				if(exclude) url += '&exclude_ids=' + exclude;
				_this.$http.get(url).then(function (response) {
					_this.$set(_this, 'options', response.body);
				});
			},
			setSelected(value, item, key) {
				this.$set(item, key, value);

				/*Reset options*/
				this.$set(this, 'options', []);
				this.$set(item, 'search', '');
			},
			addNewParent() {
				this.items.push({
					parent: {},
					childs: []
				});
			},
			setValue(value) {

			}
		},
		watch: {
			items: {
				deep: true,
				handler: function () {
					this.$emit('autocomplete-ids', JSON.stringify(this.items));
				}
			}
		}
	})
</script>