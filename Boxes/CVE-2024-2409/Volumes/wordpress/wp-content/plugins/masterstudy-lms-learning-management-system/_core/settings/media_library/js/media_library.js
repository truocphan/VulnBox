function debounce (fn, delay) {
	let timeoutID = null
	return function () {
		clearTimeout(timeoutID)
		timeoutID = setTimeout(() => {
			fn.apply(this, arguments)
		}, delay)
	}
}

Vue.component('media-library', {
	props: {
		mediaLibraryStatus: {
			type: Boolean
		},
	},
	data() {
		return {
			mediaLibraryStatus: this.mediaLibraryStatus,
			files: [],
			empty: null,
			filesLength: 0,
			editFile: {},
			loading: false,
			timeout: null,
			searchText: '',
			filter: {
				fileType: 'all',
				sortBy: 'post_date',
			},
			filesCount: {
				perPage: 20,
				offset: 0
			}
		}
	},
	components: ['media-library-input', 'upload-file-component'],
	methods: {
		handleScroll(el) {
			if((el.srcElement.offsetHeight + el.srcElement.scrollTop) >= el.srcElement.scrollHeight) {
				if (this.files.length !== this.filesLength || this.filesLength > this.filesCount) {
					this.loadMore()
				}
			}
		},

		closeModal() {
			this.$emit('modalClosed');
			document.body.classList.remove('media_open');
		},

		checkFile(file) {
			this.$emit('checkImage', file)
			this.closeModal()
		},

		async loadMore() {
			this.filesCount.offset = this.filesCount.perPage;
			this.filesCount.perPage = this.filesCount.perPage +20;

			await this.$http.get(stm_lms_ajaxurl, {
				params: {
					action: 'stm_lms_pro_media_library_get_files',
					filter: this.filter,
					filesCount: this.filesCount,
					nonce: stm_lms_pro_nonces['stm_lms_media_library_get_files']
				}
			}).then(response => {
				response.body.result.forEach(item => {
					this.files.push(item)
				})
			})
		},

		async get_all_files() {
			this.loading = true
			const response =  await this.$http.get(stm_lms_ajaxurl, {
				params: {
					action: 'stm_lms_pro_media_library_get_files',
					filter: this.filter,
					filesCount: this.filesCount,
					nonce: stm_lms_pro_nonces['stm_lms_media_library_get_files']
				}
			})
			
			if (response.body.count === 0) {
				this.loading = false
				this.empty = true
				return [];
			} else {
				this.empty = false;
				this.loading = false
				this.filesLength = response.body.count
				return response.body.result.map(item => {
					item.deleted = false
					return item
				})
			}
		},

		async updateList() {
			this.files = await this.get_all_files()
		},

		/**
		 * Search files via query
		 * @param searchQuery {string}
		 */
		searchFiles(searchQuery) {
			this.loading = true
			this.$http.get(stm_lms_ajaxurl, {
				params: {
					action: 'stm_lms_pro_media_library_search_file',
					text: searchQuery,
					filter: this.filter,
					nonce: stm_lms_pro_nonces['stm_lms_media_library_search_file']
				}
			}).then(response => {
				this.files = response.body.result
				this.empty = this.files.length == 0 ? true : false;
				this.loading = false
			})
		},

		addFile(file) {
			this.empty = null;
			this.files.unshift(file)
		},

		removeById(id) {
			const index = this.files.findIndex(item => item.id === id)
			this.files.splice(index, 1)
		}
	},

	watch: {
		mediaLibraryStatus() {
			if (this.mediaLibraryStatus) {
				this.updateList();
				document.body.classList.add('media-library')
			}
		},
		
		searchText: debounce(function(val) {
			if (val.length) {
				this.searchFiles(val)
			} else {
				this.updateList();
			}
		}, 300),

		filter: {
			deep: true,
			handler() {
				this.searchText = '';
				this.filesCount.perPage = 20
				this.filesCount.offset = 0
				this.updateList()
			}
		},
	},

})