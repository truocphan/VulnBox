Vue.component('media-library-input', {
	props: ['files_ext'],
	data() {
		return {
			progressBar: 0,
			loading: false,
			files_ext: this.files_ext,
			file_size: 1,
			file_path: '',
			error: {
				status: false,
				message: ''
			}
		}
	},

	methods: {
		clearUpload() {
			this.previousRequest.abort();
			this.loading = false;
			this.cleanImage();
		},
		
		openDialog() {
			if (Array.isArray(this.$refs['uploadInput'])) {
				this.$refs['uploadInput'][0].click();
			} else {
				this.$refs['uploadInput'].click()
			}
		},
		
		closeInput() {
			this.error.status = false
			this.loading = false
		},
		
		uploadImage(event) {
			let _this = this;
			let fileToUpload = event.target.files[0];
			this.loading = true;
			if (fileToUpload) {
				this.file_path = fileToUpload.name
				let formData = new FormData();
				formData.append('file', fileToUpload);
				formData.append('action', 'stm_lms_upload_media_library_file');
				formData.append('nonce', stm_lms_pro_nonces['stm_lms_pro_upload_image']);

				_this.$http.post(stm_lms_ajaxurl, formData, {
					before(request){
						if (this.previousRequest) {
							this.previousRequest.abort();
						}
						// set previous request on Vue instance
						this.previousRequest = request;
					},
					progress: (e) => {
						this.progressBar = Math.floor((e.loaded / e.total ) * 100)
					}
				}).then(function (res) {
					if (res.body.error) {
						this.error.status = true
						this.error.message = res.body.message
					} else {
						const file = res.body.file
						this.$emit('fileAdded', file)
						this.loading = false
						this.cleanImage()
					}

				});
			}
		},

		cleanImage() {
			this.progressBar = 0;
		}
	},
	computed: {
		fileName() {
			let fileName = this.file_path
			if (fileName.length > 25) {
				fileName = fileName.slice(0, 24) + '...'
			}
			return fileName
		},
	},
})