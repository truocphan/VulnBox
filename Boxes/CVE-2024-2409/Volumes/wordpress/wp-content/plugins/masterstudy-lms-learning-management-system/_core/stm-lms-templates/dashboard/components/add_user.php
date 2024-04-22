<transition name="slide">

	<div class="add_user">


		<div class="add_user__btn_wrapper">

			<a href="#" class="add_user__btn" @click.prevent="active = true" v-if="!active">

				<i class="fa fa-user-plus"></i>

				<?php esc_html_e( 'Add student', 'masterstudy-lms-learning-management-system' ); ?>

			</a>

		</div>

		<div class="add_user_box_overlay" v-if="active" @click.prevent="active = false"></div>

		<div class="add_user_box" v-if="active">

			<i class="fa fa-envelope"></i>

			<h3><?php esc_html_e( 'Invite student to this course:', 'masterstudy-lms-learning-management-system' ); ?>
				{{title}}</h3>

			<span>
				<?php esc_html_e( 'Enter a student’s email. If the student isn’t registered on this site, the system will create user credentials.', 'masterstudy-lms-learning-management-system' ); ?>
			</span>

			<input type="email"
					v-model="email"
					v-on:keyup.enter="addStudent()"
					required
					placeholder="<?php esc_attr_e( 'Enter student email', 'masterstudy-lms-learning-management-system' ); ?>"/>

			<div class="add_user_box_actions">

				<a href="#"
					class="button button-secondary"
					v-bind:class="{'loading' : loading}"
					@click.prevent="addStudent()">
					<span><?php esc_html_e( 'Send an invitation', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>

			</div>

			<transition name="slide-fade">
				<div class="stm-lms-message" v-bind:class="status" v-if="message">
					{{ message }}
				</div>
			</transition>

		</div>


	</div>

</transition>
