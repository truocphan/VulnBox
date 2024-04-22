<?php
stm_lms_register_style( 'bundles/card' );
?>


<div class="stm_lms_single_bundle_card" v-for="bundle in bundles"
		v-bind:class="{'overcoursed' : bundle.courses.length > 3, 'stm_lms_single_bundle_card_draft' : bundle.status === 'draft'}">

	<div class="stm_lms_single_bundle_card__inner">

		<?php STM_LMS_Templates::show_lms_template( 'bundles/card/vue/top' ); ?>

		<div class="stm_lms_single_bundle_card__center_bottom">

			<div class="stm_lms_single_bundle_card__center_bottom_inner">

				<div class="stm_lms_single_bundle_card__center">
					<?php STM_LMS_Templates::show_lms_template( 'bundles/card/vue/courses' ); ?>
				</div>

				<div class="stm_lms_single_bundle_card__bottom">
					<?php STM_LMS_Templates::show_lms_template( 'bundles/card/vue/price_rating' ); ?>
				</div>

			</div>


		</div>

	</div>

</div>

<div v-if="bundles.length === 0" class="col-md-12"><h3><?php esc_html_e( 'Sorry, Bundles not found', 'masterstudy-lms-learning-management-system-pro' ); ?></h3></div>
