<?php  // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName.
$terms = wp_get_post_terms( get_the_ID(), 'stm_lms_course_taxonomy' );
if ( ! empty( $terms ) ) {
	?>
	<div class="pull-left xs-product-cats-left">
		<div class="meta-unit categories clearfix">
			<div class="pull-left">
				<i class="fa-icon-stm_icon_category secondary_color"></i>
			</div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Category:', 'masterstudy-lms-learning-management-system' ); ?></div>
				<div class="value h6">
					<?php
					$links = array_map(
						function( $term ) {
							return sprintf(
								'<a href="%s">%s</a>',
								esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $term->term_id . '&category[]=' . $term->term_id ),
								esc_html( $term->name )
							);
						},
						$terms
					);
					echo wp_kses_post( implode( ', ', $links ) );
					?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
