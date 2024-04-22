<div class="add_items_nav">

	<div class="add_item_nav_title quiz"
		 @click="tab = 'new_quiz'"
		 v-bind:class="{'active' : tab === 'new_quiz'}">
		<?php STM_LMS_Helpers::print_svg( 'assets/svg/quiz.svg' ); ?>
		<?php esc_html_e( 'Quiz questions', 'masterstudy-lms-learning-management-system' ); ?>
	</div>

	<div class="add_item_nav_title question_bank"
		 @click="tab = 'question_bank'"
		 v-bind:class="{'active' : tab === 'question_bank'}">
		<i class="fa fa-stream"></i>
		<?php esc_html_e( 'Question bank', 'masterstudy-lms-learning-management-system' ); ?>
	</div>

	<div class="add_item_nav_title search" @click="search = true;">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
			<defs></defs>
			<path id="Forma_1" data-name="Forma 1"
				  d="M15.8,14.855L11.25,10.31a6.338,6.338,0,1,0-.942.942L14.854,15.8A0.666,0.666,0,1,0,15.8,14.855ZM6.335,11.33a5,5,0,1,1,4.994-5A5,5,0,0,1,6.335,11.33Z"
				  class="cls-1"></path>
		</svg>
		<?php esc_html_e( 'Search', 'masterstudy-lms-learning-management-system' ); ?>
	</div>

</div>
