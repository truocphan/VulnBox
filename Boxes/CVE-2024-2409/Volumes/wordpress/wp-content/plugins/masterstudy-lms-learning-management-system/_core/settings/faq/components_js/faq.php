<script>
	<?php
	ob_start();
	include STM_LMS_PATH .'/settings/faq/components/faq.php';
	$template = preg_replace( "/\r|\n/", "", addslashes(ob_get_clean()));
	?>

	Vue.component('stm-faq', {
		props: ['stored_faq'],
		data: function () {
			return {
				faq: [],
				isEmpty: false,
				timeout: ''
			}
		},
		mounted: function() {
			if(this.stored_faq) {
				this.faq = JSON.parse(this.stored_faq);
			}
		},
		template: '<?php echo stm_wpcfto_filtered_output($template); ?>',
		methods: {
			addNew: function() {
				var vm = this;

				this.faq.push({
					'question': '',
					'answer': '',
				});
			},
			deleteItem: function(key) {
				var r = confirm("Delete FAQ Item");
				if(r) this.faq.splice(key, 1);
			},
		},
		watch: {
			faq: {
				handler: function () {
					this.$emit('get-faq', JSON.stringify(this.faq));
				},
				deep: true
			}
		}
	})
</script>