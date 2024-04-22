<?php

$values = ( ! empty( $_GET['category'] ) ) ? $_GET['category'] : array( $category ?? '' );

$terms = get_terms(
	'stm_lms_course_taxonomy',
	array(
		'orderby' => 'count',
		'order'   => 'DESC',
		'parent'  => false,
	)
);

$parents = array();

if ( ! empty( $terms ) ) : ?>

	<div class="stm_lms_courses__filter stm_lms_courses__category">

		<div class="stm_lms_courses__filter_heading">
			<h3><?php esc_html_e( 'Category', 'masterstudy-lms-learning-management-system' ); ?></h3>
			<div class="toggler"></div>
		</div>

		<div class="stm_lms_courses__filter_content" style="display: none;">

			<?php
			foreach ( $terms as $term ) :
				$parents[] = $term->term_id;
				?>

				<div class="stm_lms_courses__filter_category">
					<label class="stm_lms_styled_checkbox">
					<span class="stm_lms_styled_checkbox__inner">
						<input type="checkbox"
							<?php
							if ( in_array( intval( $term->term_id ), $values ) ) {
								echo 'checked="checked"';}
							?>
							value="<?php echo intval( $term->term_id ); ?>"
							name="category[]"/>
						<span><i class="fa fa-check"></i> </span>
					</span>
						<span><?php echo esc_html( $term->name ); ?></span>
					</label>
				</div>

			<?php endforeach; ?>

		</div>

	</div>

	<?php
	set_transient( 'stm_lms_parent_categories', $parents );
endif;
