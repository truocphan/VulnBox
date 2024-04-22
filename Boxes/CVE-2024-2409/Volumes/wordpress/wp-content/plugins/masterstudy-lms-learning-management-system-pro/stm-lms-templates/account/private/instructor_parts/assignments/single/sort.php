<div class="sort_assignments">
	<span @click="openSort = true"><?php esc_html_e( 'Sort by', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
	<div class="sort_assignments__statuses" :class="{'active_sort' : openSort}">
		<div class="active" v-html="activeSort" @click="openSort = !openSort">
			{{activeSort}}
		</div>
		<div class="sort_assignments__statuses_available">
			<div class="sort_assignments__status"
					v-for="(sortStatus, sortSlug) in sortStatuses"
					v-if="sortStatus !== activeSort"
					@click="activeSort = sortStatus; sort = sortSlug; openSort = false"
					v-html="sortStatus">
			</div>
		</div>
	</div>
</div>
