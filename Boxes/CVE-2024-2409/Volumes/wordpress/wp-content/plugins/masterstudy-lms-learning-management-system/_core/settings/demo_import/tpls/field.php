<div class="wpcfto_generic_field wpcfto_generic_field_flex_input wpcfto_generic_field_demo_import">
	<label><?php esc_html_e( 'Import Demo courses', 'masterstudy-lms-learning-management-system' ); ?></label>
	<div v-bind:class="doneSteps + ' import_steps'" v-if="importStarted">
		<div class="step step-questions">
			<div class="step-wrap">
				<span class="bullet"><i class="fa fa-check"></i></span>
				<span class="step-name"><?php esc_html_e( 'Questions', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		</div>
		<div class="step step-quizzes">
			<div class="step-wrap">
				<span class="bullet"><i class="fa fa-check"></i></span>
				<span class="step-name"><?php esc_html_e( 'Quizzes', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		</div>
		<div class="step step-lessons">
			<div class="step-wrap">
				<span class="bullet"><i class="fa fa-check"></i></span>
				<span class="step-name"><?php esc_html_e( 'Lessons', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		</div>
		<div class="step step-courses">
			<div class="step-wrap">
				<span class="bullet"><i class="fa fa-check"></i></span>
				<span class="step-name"><?php esc_html_e( 'Courses', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		</div>
		<div class="step step-complete">
			<div class="step-wrap">
				<span class="bullet"><i class="fa fa-check"></i></span>
				<span class="step-name"><?php esc_html_e( 'Complete', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		</div>
	</div>
		<p v-if="!importStarted">
	<?php esc_html_e( 'When you click Import Demo Course all demo courses, quizzes and lessons will be imported to your site', 'masterstudy-lms-learning-management-system' ); ?></p>
		<button v-if="!importStarted" @click.prevent="importData()" class="button">
			<i class="fa fa-cloud-download-alt"></i>
			<?php esc_html_e( 'Start import', 'masterstudy-lms-learning-management-system' ); ?>
		</button>
</div>
