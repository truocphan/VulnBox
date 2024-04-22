<div>

	<div v-if="loading">
		<p><?php esc_html_e( 'Loading Courses...', 'masterstudy-lms-learning-management-system-pro' ); ?></p>
	</div>

	<div v-else>

		<div class="stm_lms_material_buttons">
			<a href="#" class="stm_lms_material_button" @click.prevent="importAll">
				<span><?php esc_html_e( 'Import all', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</a>

			<a href="#" class="stm_lms_material_button" @click.prevent="publishAll">
				<span><?php esc_html_e( 'Publish all imported', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
			</a>

		</div>

		<table >

			<thead>
			<tr>
				<th class="name"><?php esc_html_e( 'Name', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th class="auditory"><?php esc_html_e( 'Auditory', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th class="status"><?php esc_html_e( 'Status', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th class="import"><?php esc_html_e( 'Import', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
			</tr>
			</thead>

			<tbody>
			<tr v-for="course in courses">
				<td class="name" v-html="course.name"></td>
				<td class="auditory" v-html="course.auditory"></td>
				<td class="status">
					<span v-if="!course.action_links"><?php esc_html_e( 'Not Imported Yet', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					<div v-else>
						<span v-html="course.action_links.status"></span>
					</div>
				</td>
				<td class="import">

					<div class="stm_lms_material_button" @click="importCourse(course)"
							v-if="!course.action_links"
							:class="{'loading' : course.loading }">
						<i class="lnr lnr-download" v-if="!course.loading"></i>
						<i class="lnr lnr-sync" v-else></i>
						<span><?php esc_html_e( 'Import Course', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					</div>

					<div class="stm_lms_material_buttons" v-else>

						<a :href="course.action_links.course_url"
							v-if="course.action_links.course_url"
							class="stm_lms_material_button"
							target="_blank">
							<span><?php esc_html_e( 'Preview', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
						</a>

						<a :href="course.action_links.course_url_edit"
							v-if="course.action_links.course_url_edit"
							class="stm_lms_material_button"
							target="_blank">
							<span><?php esc_html_e( 'Edit', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
						</a>

						<a href="#" class="stm_lms_material_button"
							:class="{'loading' : course.loading }"
							target="_blank"
							@click.prevent="publishCourse(course)"
							v-if="course.action_links.status === 'draft'">
							<i class="lnr lnr-sync" v-if="course.loading"></i>
							<span><?php esc_html_e( 'Publish', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
						</a>

						<a href="#" class="stm_lms_material_button loading"
							v-if="course.action_links.status === 'publish' && course.loading"
							@click.prevent>
							<i class="lnr lnr-sync"></i>
						</a>

					</div>

				</td>
			</tr>
			</tbody>

		</table>

	</div>

</div>
