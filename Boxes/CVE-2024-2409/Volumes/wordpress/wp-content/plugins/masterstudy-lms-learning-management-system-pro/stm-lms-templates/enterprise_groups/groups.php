<div class="stm_lms_ent_groups" v-bind:class="{'active' : groupData.group_id, 'empty-groups' : !groups.length}">
	<div class="stm_lms_ent_groups_list" v-if="groups.length">
		<h2><?php esc_html_e( 'My Groups', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>
		<div class="stm_lms_ent_groups_single"
				v-bind:class="{'active' : group.group_id === groupData.group_id}"
				v-for="group in groups">
			<h4 @click.prevent="groupData = group">
				<span v-html="group.title"></span>
			</h4>
			<div class="actions">
				<a v-bind:href="group.url" target="_blank"><i class="lnricons-eye"></i></a>
				<i class="lnricons-pencil3" @click="groupData = group"></i>
				<i class="lnricons-cross" @click.prevent="deleteGroup(group)"></i>
			</div>
		</div>
	</div>

	<h4 v-else>
		<i class="lnricons-ghost"></i>
		<?php esc_html_e( 'No groups found.', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</h4>

	<div class="import_groups">
		<transition name="slide-fade">
			<div class="file_message_error" v-if="file_message" v-html="file_message"></div>
		</transition>

		<label class="import_groups__file" v-html="file.name" @click="clearFile()" v-if="file.name"></label>
		<a href="#" class="btn btn-default btn-import" @click.prevent="submitFile()" v-if="file.name">
			<?php esc_html_e( 'Create Groups', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>

		<div class="import_groups__inner" v-else>
			<input type="file" ref="lms_group_csv" @change="handleFileUpload()"/>
			<a href="#" class="btn btn-default">
				<?php esc_html_e( 'Import Groups (csv)', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>
		</div>
	</div>

</div>
