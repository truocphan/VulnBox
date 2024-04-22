<?php
stm_lms_register_style( 'point_admin_stats' );
stm_lms_register_script( 'point_admin_stats', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-point_admin_stats',
	'stm_lms_point_stats',
	array(
		'data'        => STM_LMS_Point_System_Statistics::users(),
		'translation' => array(
			'delete_action' => esc_html__( 'Do you really want to delete this record?', 'masterstudy-lms-learning-management-system-pro' ),
		),
	)
);

?>

<div class="wrap">
	<div id="stm_lms_admin_point_stats">

		<input type="text"
				v-model="search"
				v-on:keyup.enter="page=1; getUsers()"
				placeholder="<?php esc_attr_e( 'Search by user', 'masterstudy-lms-learning-management-system-pro' ); ?>">

		<div class="inner" v-bind:class="{'loading' : loading}">

			<h2><?php esc_html_e( 'Student Points', 'masterstudy-lms-learning-management-system-pro' ); ?></h2>

			<table class="wp-list-table widefat">
				<thead>
				<tr>
					<th class="login"><?php esc_html_e( 'User', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
					<th class="points"><?php esc_html_e( 'Points', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
					<th class="history"><?php esc_html_e( 'History', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="user in users">
					<td colspan="3" class="users_td">
						<table>
							<tr>
								<td class="login"><strong>{{user['data']['user_login']}}</strong></td>
								<td class="points">{{user['data']['lms_data']['points']}}</td>
								<td class="history"
									@click="user['data']['lms_data']['opened'] = !user['data']['lms_data']['opened']; loadHistory(user, 1);">
									<i class="lnricons-chevron-down" v-if="!user['data']['lms_data']['opened']"></i>
									<i class="lnricons-chevron-up" v-else></i>
								</td>
							</tr>
							<tr v-if="user['data']['lms_data']['opened']">
								<td colspan="3">
									<?php STM_LMS_Templates::show_lms_template( 'points/admin/actions' ); ?>
								</td>
							</tr>
						</table>
				</tr>

				</tbody>
			</table>

			<div class="asignments_grid__pagination" v-if="pages !== 1">
				<ul class="page-numbers">
					<li v-for="single_page in pages">
						<a class="page-numbers"
							href="#"
							v-if="single_page !== page"
							@click.prevent="page = single_page; getUsers();">
							{{single_page}}
						</a>
						<span v-else class="page-numbers current">{{single_page}}</span>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
