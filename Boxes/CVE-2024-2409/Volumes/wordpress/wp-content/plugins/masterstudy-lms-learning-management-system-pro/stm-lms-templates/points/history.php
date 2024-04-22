<?php
stm_lms_register_style( 'points_history' );
stm_lms_register_script( 'points_history', array( 'vue.js', 'vue-resource.js' ) );
wp_localize_script(
	'stm-lms-points_history',
	'stm_lms_points_history',
	array(
		'result' => STM_LMS_Point_History::points(),
	)
);

stm_lms_register_style( 'my-points' );
$user = STM_LMS_User::get_current_user();

?>

<div id="stm_lms_points_history">

	<div class="stm_lms_points_history__head">
		<div class="left">
			<h2>
				<?php
				printf(
					/* translators: %s Points Label */
					esc_html__( 'Your %s', 'masterstudy-lms-learning-management-system-pro' ),
					STM_LMS_Point_System::get_label() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
				?>
			</h2>
			<a href="<?php echo esc_url( ms_plugin_user_account_url( 'points-distribution' ) ); ?>">
				<?php esc_html_e( 'How to get more?', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>
		</div>
		<div class="right">
			<div class="stm-lms-my-points">

				<?php echo STM_LMS_Point_System::display_point_image(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="points-inner">
					<span><?php esc_html_e( 'You have', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					<label><?php echo STM_LMS_Point_System::display_points( STM_LMS_Point_System::total_points( $user['id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
				</div>
			</div>
		</div>
	</div>

	<div class="multiseparator"></div>

	<div class="stm_lms_points_history_table" v-bind:class="{'loading' : loading}">

		<table>
			<thead>
			<tr>
				<th><?php esc_html_e( 'Event', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th><?php esc_html_e( 'Origin', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th><?php esc_html_e( 'Date', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
				<th><?php echo STM_LMS_Point_System::get_label(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
			</tr>
			</thead>
			<tbody>
			<tr v-for="point in points" v-bind:class="{'plus' : point.score > 0, 'minus' : point.score < 0 }">
				<td class="point-label" v-html="point.data.label"></td>
				<td class="point-title">
					<span v-if="point.title" v-html="point.title"></span>
				</td>
				<td class="point-time" v-html="point.timestamp"></td>
				<td class="point-score" v-html="point.score"></td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3" class="point-sum-label heading_font">
					<?php
					printf(
						/* translators: %s Points Label */
						esc_html__( 'Total %s ', 'masterstudy-lms-learning-management-system-pro' ),
						STM_LMS_Point_System::get_label() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);
					?>
				</td>
				<td class="points-sum">{{sum}}</td>
			</tr>
			</tfoot>
		</table>

		<div class="asignments_grid__pagination" v-if="pages !== 1">
			<ul class="page-numbers">

				<li v-for="single_page in pages">

					<a class="page-numbers"
							href="#"
							v-if="single_page !== page"
							@click.prevent="page = single_page; getPoints()"
							v-bind:class="computePage(single_page)">
						{{single_page}}
					</a>
					<span v-else class="page-numbers current">{{single_page}}</span>
				</li>
			</ul>
		</div>

	</div>

</div>
