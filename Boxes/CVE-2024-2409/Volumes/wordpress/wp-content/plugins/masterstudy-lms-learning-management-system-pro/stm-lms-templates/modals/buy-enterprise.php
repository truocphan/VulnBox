<?php
/**
 *
 * @var $course_id
 */
?>

<div class="modal fade stm-lms-modal-buy-enterprise" tabindex="-1" role="dialog" aria-labelledby="stm-lms-modal-buy-enterprise">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php STM_LMS_Templates::show_lms_template( 'enterprise_groups/enterprise-buy-modal', compact( 'course_id' ) ); ?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
