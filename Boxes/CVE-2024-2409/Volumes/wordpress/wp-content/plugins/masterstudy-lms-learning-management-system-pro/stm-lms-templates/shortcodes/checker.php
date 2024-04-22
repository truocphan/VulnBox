<?php
/**
 *
 * @var $title
 */

stm_lms_register_style( 'certificate_checker' );
stm_lms_register_script( 'certificate_checker', array( 'vue.js', 'vue-resource.js' ) );
?>

<div id="certificate_checker">

	<div class="stm_lms_certificate_checker" v-bind:class="{'loading' : loading}">

		<h3><?php echo esc_html( $title ); ?></h3>

		<div class="stm_lms_certificate_checker__form">

			<div class="stm_lms_certificate_checker__input">

				<input type="text" v-model="code" class="form-control" placeholder="<?php esc_attr_e( 'Enter certificate code', 'masterstudy-lms-learning-management-system-pro' ); ?>"/>

			</div>

			<a href="#" @click.prevent="checkCode()" class="btn btn-default">
				<?php esc_html_e( 'Check Code', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>


		</div>

		<transition name="slide-fade">
			<div class="stm-lms-message" v-bind:class="status" v-if="message" v-html="message"></div>
		</transition>

	</div>

</div>
