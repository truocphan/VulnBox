<script type="text/javascript">
	<?php
	// phpcs:ignoreFile
	ob_start();
	include STM_LMS_PATH .'/settings/order/components/order.php';
	$template = preg_replace( "/\r|\n/", "", addslashes(ob_get_clean()));
	global $post;
	?>
	Vue.component('stm-order', {
		data: function () {
			return {
				'post_id': <?php echo intval( $post->ID ); ?>,
				'order': false,
				'i18n': [],
				'status': 'pending',
				'completed': false,
			}
		},
		template: '<?php echo stm_wpcfto_filtered_output( $template ); ?>',
		mounted: function () {
			this.getOrder(this.post_id);
		},
		methods: {
			getOrder(order_id) {
				this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_lms_get_order_info&nonce=' + stm_lms_nonces['get_order_info'] + '&order_id=' + order_id).then(function (response) {
					this.order = response.body;
					this.i18n = this.order.i18n;
					this.status = this.order.status;
					this.completed = this.status;
				});
			},
		},
	})
</script>
