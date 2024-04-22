<h5 v-if="user.loading"><?php esc_html_e( 'Loading...', 'masterstudy-lms-learning-management-system-pro' ); ?></h5>

<div class="user_history" v-else>

	<table class="wp-list-table widefat">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Event', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
			<th><?php esc_html_e( 'Origin', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
			<th><?php esc_html_e( 'Date', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
			<th class="point-score"><?php echo STM_LMS_Point_System::get_label(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
			<th class="point-actions"><?php esc_html_e( 'Actions', 'masterstudy-lms-learning-management-system-pro' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr v-for="(point, point_index) in user.history.points"
			v-bind:class="{'plus' : point.score > 0, 'minus' : point.score < 0, 'loading' : point.loading }">
			<td class="point-label" v-html="point.data.label"></td>
			<td class="point-title">
				<a v-if="point.title && point.url" v-bind:href="point.url" v-html="point.title" target="_blank"></a>
			</td>
			<td class="point-time" v-html="point.timestamp"></td>
			<td class="point-score">
				<span v-html="point.score" v-if="typeof point.editing === 'undefined' || !point.editing"></span>
				<div class="edit_points" v-else>
					<input type="number" v-model="point.edit" v-on:keyup.enter="changePoints(point, user)">
					<div class="actions-edit">
						<i class="lnricons-check" @click="changePoints(point, user)"></i>
						<i class="lnricons-cross" @click="$set(point, 'editing', false);"></i>
					</div>
				</div>
			</td>
			<td class="point-actions">
				<i class="lnricons-pencil3" @click="$set(point, 'editing', true); $set(point, 'edit', point.score.match(/\d+/g).map(Number) );"></i>
				<i class="lnricons-cross" @click="deletePoint(point, point_index, user);"></i>
			</td>
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
			<td class="points-sum">{{user.history.sum}}</td>
			<td class="point-actions"></td>
		</tr>
		</tfoot>
	</table>

	<div class="asignments_grid__pagination" v-if="user.history.pages !== 1">
		<ul class="page-numbers">
			<li v-for="single_page in user.history.pages">
				<a class="page-numbers"
						href="#"
						v-if="single_page !== user.current_page"
						@click.prevent="loadHistory(user, single_page);">
					{{single_page}}
				</a>
				<span v-else class="page-numbers current">{{single_page}}</span>
			</li>
		</ul>
	</div>
</div>
