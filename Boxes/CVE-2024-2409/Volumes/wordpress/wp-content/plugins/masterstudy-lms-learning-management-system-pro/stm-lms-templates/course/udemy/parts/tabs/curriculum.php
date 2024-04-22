<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} //Exit if accessed directly ?>

<?php
stm_lms_register_style( 'curriculum' );
stm_lms_register_script( 'curriculum' );
$post_id = ( ! empty( $post_id ) ) ? $post_id : get_the_ID();

$curriculum  = get_post_meta( $post_id, 'udemy_curriculum', true );
$item_number = 0;
if ( ! empty( $curriculum ) && ! empty( $curriculum['results'] ) ) :
	?>
	<div class="stm-curriculum">
		<?php foreach ( $curriculum['results'] as $item ) : ?>
			<?php
			if ( 'chapter' === $item['_class'] ) :
				$item_number = 0;
				?>
				<div class="stm-curriculum-section">
					<h3><?php echo sanitize_text_field( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				</div>
				<?php
			else :
				$item_number++;
				$lesson_excerpt = ( ! empty( $item['description'] ) ) ? $item['description'] : '';
				$item_type      = $item['_class'];
				if ( 'quiz' === $item_type ) {
					$icon      = 'stmlms-quiz';
					$icon_text = esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system-pro' );
				} else {
					$icon       = 'stmlms-text';
					$icon_text  = esc_html__( 'Text Lesson', 'masterstudy-lms-learning-management-system-pro' );
					$asset_type = ( ! empty( $item['asset'] ) ) ? $item['asset']['asset_type'] : 'Article';
					if ( 'Video' === $asset_type ) {
						$icon      = 'stmlms-slides';
						$icon_text = esc_html__( 'Video', 'masterstudy-lms-learning-management-system-pro' );
					}
				}
				?>
				<div class="stm-curriculum-item 
				<?php
				if ( ! empty( $lesson_excerpt ) ) {
					echo esc_attr( 'has-excerpt' );}
				?>
				">

					<div class="stm-curriculum-item__num"><?php echo intval( $item_number ); ?></div>

					<div class="stm-curriculum-item__icon"
							data-toggle="tooltip"
							data-placement="bottom"
							data-original-title="<?php echo esc_attr( $icon_text ); ?>">
						<i class="<?php echo esc_attr( $icon ); ?>"></i>
					</div>

					<div class="stm-curriculum-item__title">
						<div class="heading_font">
							<?php echo sanitize_text_field( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php if ( ! empty( $lesson_excerpt ) ) : ?>
								<span class="stm-curriculum-item__toggle"></span>
							<?php endif; ?>
						</div>
					</div>

					<div class="stm-curriculum-item__preview"></div>

					<div class="stm-curriculum-item__meta"></div>

					<?php if ( ! empty( $lesson_excerpt ) ) : ?>
						<div class="stm-curriculum-item__excerpt"><?php echo wp_kses_post( $lesson_excerpt ); ?></div>
					<?php endif; ?>

				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php else : ?>
	<?php STM_LMS_Templates::show_lms_template( 'course/parts/tabs/curriculum' ); ?>
	<?php
endif;
