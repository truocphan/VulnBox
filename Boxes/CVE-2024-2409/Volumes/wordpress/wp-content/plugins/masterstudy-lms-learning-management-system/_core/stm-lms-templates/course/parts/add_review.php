<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php wp_enqueue_script( 'vue.js' ); ?>
<?php wp_enqueue_script( 'vue-resource.js' ); ?>
<?php wp_enqueue_script( 'vue2-editor.js' ); ?>

<?php
wp_add_inline_script(
	'vue2-editor.js',
	'Vue.component("vue-editor", Vue2Editor.default.VueEditor);
    var stm_lms_post_id = ' . $post_id
);
?>

<div id="stm_lms_add_review">
	<?php if ( is_user_logged_in() ) : ?>
		<transition name="slide-fade">
			<div class="" v-if="openReview">
				<div class="form-group">
					<vue-editor v-model="review_text"></vue-editor>
				</div>
				<div class="form-group" v-bind:class="{'isRtl' : is_rtl}">
					<?php if ( is_rtl() ) : ?>
						<select v-model="rating" class="form-control disable-select">
							<option :value="0"><?php esc_html_e( 'Select rating', 'masterstudy-lms-learning-management-system' ); ?></option>
							<option :value="1">1</option>
							<option :value="2">2</option>
							<option :value="3">3</option>
							<option :value="4">4</option>
							<option :value="5">5</option>
						</select>
					<?php else : ?>
						<div class="star-rating" v-on:click="ratingHover($event)" v-bind:class="ratingClasses">
							<span v-bind:style="{'width' : ratingW(this.rating)}"></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</transition>

		<a href="#" class="btn btn-default stm-lms-review-btn" @click.prevent="addReview()"
			v-bind:class="{'loading' : loading}">
			<span><?php esc_html_e( 'Add review', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>

		<transition name="slide-fade">
			<div class="stm-lms-message" v-bind:class="status" v-if="message" v-html="message"></div>
		</transition>
	<?php else : ?>
		<?php /* translators: %s: leave review */ ?>
		<?php  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( __( 'Please, <a href="%s">login</a> to leave a review', 'masterstudy-lms-learning-management-system' ), esc_url( STM_LMS_User::login_page_url() ) );
		?>
	<?php endif; ?>
</div>
