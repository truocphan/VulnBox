<?php
/**
 * @var $id
 * @var $symbol
 */

$terms = stm_lms_get_terms_array($id, 'stm_lms_course_taxonomy', false, true);
?>

<?php if (!empty($terms)):
	$terms = array_splice($terms, 0, 1);
	?>
	<div class="stm_lms_courses__single--terms">
		<div class="stm_lms_courses__single--term">
			<?php echo sanitize_text_field(implode('', $terms) ); ?>
		</div>
	</div>
<?php endif; ?>