Vue.component('upload-file-component', {
	props: {
		file: {
			type: Object,
		},
		loading: {
			type: Boolean,
		}
	},
	data() {
		return {
			file: this.file,
			fileBody: true,
			fileEdit: false,
		}
	},

	methods: {
		async deleteFile(file) {
			if(confirm('Are you sure you want to delete this file?')) {
				file.deleted = true;
				await this.$http.post(stm_lms_ajaxurl, {
					action: 'stm_lms_pro_media_library_delete_file',
					nonce: stm_lms_pro_nonces['stm_lms_media_library_delete_file'],
					id: file.id
				}, {emulateJSON: true}).then(response => {
					this.$emit('deleteFile', file.id)
				})
			}
		},
	},
	
	computed: {
		fileName() {
			return this.file.title.length > 20 ? this.file.title.substr(0, 20) + "..." : this.file.title;
		}
	}
})