<?php
$terms  = stm_lms_get_terms_array( get_the_ID(), 'stm_lms_course_taxonomy', 'name', true );
$number = ( empty( $number ) ) ? '3' : $number;

if ( ! empty( $terms ) ) :
	?>
	<div class="pull-left xs-product-cats-left">
		<div class="meta-unit categories clearfix">
			<div class="pull-left">
				<i class="fa-icon-stm_icon_category secondary_color"></i>
			</div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Category:', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="value h6">
					<?php echo wp_kses_post( implode( ', ', array_slice( $terms, 0, $number ) ) ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
