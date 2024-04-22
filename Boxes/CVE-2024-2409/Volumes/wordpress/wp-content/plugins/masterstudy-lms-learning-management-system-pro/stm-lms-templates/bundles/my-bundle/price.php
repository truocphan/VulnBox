<div class="stm_lms_my_bundle__title">

	<h4 class="stm_lms_my_bundle__label">
		<?php
		printf(
		/* translators: %s Bundle price */
			esc_html__( 'Bundle price (%s)', 'masterstudy-lms-learning-management-system-pro' ),
			STM_LMS_Helpers::get_currency() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		?>
	</h4>

	<input type="number"
		class="form-control"
		v-model="bundle_price"
		oninput="this.value"
		placeholder="<?php esc_attr_e( 'Enter bundle price', 'masterstudy-lms-learning-management-system-pro' ); ?>"/>

</div>
