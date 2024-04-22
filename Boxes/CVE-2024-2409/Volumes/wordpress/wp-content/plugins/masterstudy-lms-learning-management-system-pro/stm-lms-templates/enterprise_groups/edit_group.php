<div class="stm_lms_ent_groups_add_edit">

	<div class="stm_lms_ent_groups_add_edit__title">
		<a href="#" v-if="groupData['group_id']" class="cancel-editing" @click.prevent="resetGroupEditing">
			<span><?php esc_html_e( 'Add new group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		</a>

		<h2 v-if="groupData['title'].length" v-html="groupData['title']"></h2>
		<h2 v-else><?php esc_attr_e( 'Add Group', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>
		<input type="text"
				class="form-control"
				v-model="groupData['title']"
				placeholder="<?php esc_attr_e( 'Enter group name', 'masterstudy-lms-learning-management-system-pro' ); ?>"/>
	</div>

	<!--EMAILS-->
	<div class="stm_lms_ent_groups_add_edit__emails">

		<h4>
			<?php esc_html_e( 'Add users', 'masterstudy-lms-learning-management-system-pro' ); ?>
			<span v-html="'(' + external_data.translations.group_limit + ' ' + external_data.limit + ')'" 
				v-bind:class="{'limit' : groupData['emails'].length < 2}">></span>
		</h4>

		<div class="stm_lms_ent_groups_add_edit__emails_new">
			<input type="text"
					v-model="newEmail"
					v-bind:class="{
						'valid' : validEmail(newEmail),
						'invalid' : !validEmail(newEmail) && newEmail.length
						}"
					class="form-control"
					placeholder="<?php esc_attr_e( 'Enter new user e-mail', 'masterstudy-lms-learning-management-system-pro' ); ?>"
					@keyup.enter="addNewEmail()"/>

			<i class="lnricons-arrow-return" v-if="validEmail(newEmail)" @click="addNewEmail()"></i>
		</div>

		<div class="stm_lms_ent_groups_add_edit__emails_list">
			<div class="stm_lms_ent_groups_add_edit__email" v-for="(email, email_index) in groupData['emails']">
				<span>{{email}}</span>
				<i class="lnricons-cross" @click="groupData['emails'].splice(email_index, 1);"></i>
			</div>
		</div>

	</div>

	<a href="#" @click.prevent="addGroup" class="btn btn-default" v-bind:class="{'disabled' : !groupData['title'] || !groupData['emails'].length }">
		<span v-if="!groupData['group_id']"><?php esc_html_e( 'Add group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		<span v-else><?php esc_html_e( 'Edit group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	</a>

	<transition name="slide-fade">
		<div class="stm-lms-message" v-bind:class="status" v-if="message" v-html="message"></div>
	</transition>

</div>

