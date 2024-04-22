<transition name="slide">

	<div class="stm-lms-dashboard-inner stm-lms-dashboard-course">

		<div class="loading" v-if="loading"></div>

		<div class="inner_course" v-else>

			<div class="stm-lms-dashboard-course--heading">

				<div class="titles">
					<h5 v-if="students.length">
						<?php echo esc_html__( 'All', 'masterstudy-lms-learning-management-system' ); ?>
						{{students.length}}
						<?php esc_html_e( 'students of', 'masterstudy-lms-learning-management-system' ); ?></h5>
					<h2 v-html="title"></h2>
				</div>

				<div class="searchboxes">
					<add_user :course_id="id" v-on:studentAdded="studentAdded" :title="origin_title"></add_user>
					<div class="searchbox-wrapper">
						<i class="fa fa-search"></i>
						<input type="text" v-model="search"
							placeholder="<?php esc_attr_e( 'Search student...', 'masterstudy-lms-learning-management-system' ); ?>">
					</div>
				</div>

			</div>

			<div class="lms-dashboard-table" v-if="students.length">

				<table>
					<thead>
					<tr>
						<th class="name">
							<div class="sort-table" @click="sortBy('name')"
								v-bind:class="[sort === 'name' ? 'active' : '', 'direction_' + sortDirection]">
								<i class="fa fa-long-arrow-alt-up"></i>
								<i class="fa fa-long-arrow-alt-down"></i>
								<?php esc_html_e( 'Student name', 'masterstudy-lms-learning-management-system' ); ?>
							</div>
						</th>
						<th class="email">
							<div class="sort-table" @click="sortBy('email')"
								v-bind:class="[sort === 'email' ? 'active' : '', 'direction_' + sortDirection]">
								<i class="fa fa-long-arrow-alt-up"></i>
								<i class="fa fa-long-arrow-alt-down"></i>
								<?php esc_html_e( 'Student email', 'masterstudy-lms-learning-management-system' ); ?>
							</div>
						</th>
						<th class="time">
							<div class="sort-table" @click="sortBy('time')"
								v-bind:class="[sort === 'time' ? 'active' : '', 'direction_' + sortDirection]">
								<i class="fa fa-long-arrow-alt-up"></i>
								<i class="fa fa-long-arrow-alt-down"></i>
								<?php esc_html_e( 'Started', 'masterstudy-lms-learning-management-system' ); ?>
							</div>
						</th>
						<th class="progress_cell">
							<div class="sort-table" @click="sortBy('progress')"
								v-bind:class="[sort === 'progress' ? 'active' : '', 'direction_' + sortDirection]">
								<i class="fa fa-long-arrow-alt-up"></i>
								<i class="fa fa-long-arrow-alt-down"></i>
								<?php esc_html_e( 'Progress', 'masterstudy-lms-learning-management-system' ); ?>
							</div>
						</th>
						<th class="student_progress"></th>
						<?php
						if ( is_ms_lms_addon_enabled( 'coming_soon' ) && STM_LMS_Helpers::is_pro_plus() ) {
							?>
							<th class="subscribed_column">
								<?php echo esc_html__( 'Subscribed', 'masterstudy-lms-learning-management-system' ); ?>
							</th>
							<?php
						}
						?>
						<th class="delete"></th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(list, list_key) in studentsList"
						v-bind:class="{'table_loading' : list.loading}">


						<td class="name">

							<div class="author">
								<div class="img" v-html="list.student.avatar"></div>
								<div class="author__info">
									<h5 v-html="list.student.login"></h5>
								</div>
							</div>
						</td>

						<td class="email" v-html="list.student.email"></td>

						<td v-html="list.ago" class="time"></td>

						<td class="progress_cell" @click="toUser(id, list.user_id)">
							<div class="progress-wrapper" v-if="list.user_id">
								<div class="progress">
									<div class="progress-bar progress-bar-success"
										v-bind:class="{'active progress-bar-striped' : list.progress_percent < 100}"
										v-bind:style="{'width': list.progress_percent + '%'}"></div>
								</div>
								<div class="progress-label">{{list.progress_percent}}%</div>
							</div>
						</td>


						<td class="student_progress">
							<div class="goToProgress" @click="toUser(id, list.user_id)" v-if="list.user_id">
								<i class="fa fa-list"></i>
								<?php esc_html_e( 'Progress', 'masterstudy-lms-learning-management-system' ); ?>
							</div>
						</td>
						<?php
						if ( is_ms_lms_addon_enabled( 'coming_soon' ) && STM_LMS_Helpers::is_pro_plus() ) {
							?>
							<td v-html="list.subscribed_time" class="time"></td>
							<?php
						}
						?>

						<td class="delete">

							<i class="lnr lnr-trash" @click="deleteUserCourse(id, list, list_key)"></i>

						</td>
					</tr>

					</tbody>
				</table>

				<div class="filter">
					<div class="filter_single">
						<label><?php esc_html_e( 'Show on page', 'masterstudy-lms-learning-management-system' ); ?></label>
						<select v-model="limit" @change="page = 1">
							<option v-bind:value="20">20</option>
							<option v-bind:value="30">30</option>
							<option v-bind:value="40">40</option>
							<option v-bind:value="50">50</option>
							<option v-bind:value="100">100</option>
						</select>
					</div>
					<div class="filter_single">
						<label><?php esc_html_e( 'Page', 'masterstudy-lms-learning-management-system' ); ?></label>
						<select v-model="page">
							<option v-for="n in pages" v-bind:value="n">{{n}}</option>
						</select>
					</div>

				</div>

			</div>

			<h4 v-else><?php esc_html_e( 'No students in course yet...', 'masterstudy-lms-learning-management-system' ); ?></h4>

		</div>


	</div>

</transition>
