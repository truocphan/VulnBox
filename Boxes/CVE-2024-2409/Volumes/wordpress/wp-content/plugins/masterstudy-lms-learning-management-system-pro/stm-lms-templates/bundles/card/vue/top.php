<div class="stm_lms_single_bundle_card__top heading_font">

	<a v-bind:href="bundle.url" class="stm_lms_single_bundle_card__title" v-html="bundle.title"></a>

	<div class="stm_lms_single_bundle_card__top_subplace">

		<a href="#" @click.prevent="changeStatusBundle(bundle)" v-if="bundle.status==='publish'">
			<span>
				<i class="fa fa-eye-slash"></i>
				<?php esc_html_e( 'Draft', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</a>

		<a href="#" @click.prevent="changeStatusBundle(bundle)" v-else>
			<span>
				<i class="fa fa-check"></i>
				<?php esc_html_e( 'Publish', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</a>

		<a v-bind:href="bundle.edit_url">
			<span>
				<i class="fa fa-pencil-alt"></i>
				<?php esc_html_e( 'Edit', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</a>

		<a href="#" @click.prevent="deleteBundle(bundle)">
			<span>
				<i class="fa fa-trash-alt"></i>
				<?php esc_html_e( 'Delete', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</a>

	</div>

</div>
