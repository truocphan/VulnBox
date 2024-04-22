<script>

	Vue.component('send_email', {
		props: ['fields', 'field_label', 'field_name', 'field_nonce', 'field_id', 'field_ajax', 'field_value'],
		data: function () {
			return {
				value : '',
				nonce : '',
			}
		},
		template: `
        <div class="send-test-email-btn">
			<button  type="submit"
				v-bind:name="field_name"
                v-bind:id="field_id"
				@click="clickAction($event)"
                v-model="value" class="button">
				Send Test Email
					<i class="fa fa-refresh fa-spin installing"></i>
					<i class="fa fa-check downloaded" aria-hidden="true"></i>
			</button>
			<p class="send-error-email-result">There was an error while sending the Email. Please try again later.</p>

        </div>
    `,
		mounted: function () {
			this.value = this.field_value;
		},
		methods: {
			clickAction (event) {
				var ajax_url= this.field_ajax;
				var nonce= this.field_nonce;
				var email= this.value;
				var btn_el = (event.target);

				(function( $ ) {
					var getClass = this.className;
					$(btn_el).find('.downloaded').css('display','none');
					$(btn_el).find('.installing').css('display','block');

					$.ajax({
						url: ajax_url,
						type: 'POST',
						data: {
							action: 'stm_lms_send_test_email_ajax',
							nonce: nonce,
							emailId: email
						},
						success: function () {
							$(btn_el).find('.installing').css('display','none');
							$(btn_el).find('.downloaded').css('display','block');
						},
						error: function () {
							$(btn_el).next('.send-error-email-result').css('display','block');
						},
					});
				})(jQuery)
			}
		},
		watch: {
			value: function (value) {
				this.$emit('wpcfto-get-value', value);
			}
		}
	});
</script>