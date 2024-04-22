<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $question_view_type
 */

$question_id = get_the_ID();
stm_lms_register_style( 'image_match_question' );
wp_enqueue_script( 'jquery-ui-sortable' );
stm_lms_register_script( 'jquery.ui.touch-punch.min' );
stm_lms_register_script( 'image_match_question', array( 'stm-lms-jquery.ui.touch-punch.min' ) );
?>

<div class="stm_lms_question_image_match <?php echo ! empty( $question_view_type ) ? esc_attr( $question_view_type ) : ''; ?>" data-id="<?php echo esc_attr( $question_id ); ?>">

	<div class="stm_lms_question_image_match__questions">
		<?php foreach ( $answers as $answer ) { ?>
			<div class="stm_lms_question_image_match__row">
				<div class="stm_lms_question_image_match__single">
					<div class="image_match_answer">
						<div class="image_box <?php echo empty( $answer['question_image']['url'] ) ? 'empty' : ''; ?>">
							<?php if ( ! empty( $answer['question_image']['url'] ) ) { ?>
								<img src="<?php echo esc_url( $answer['question_image']['url'] ); ?>"/>
							<?php } ?>
						</div>
						<?php if ( ! empty( $answer['question'] ) ) { ?>
							<span><?php echo wp_kses_post( $answer['question'] ); ?></span>
						<?php } ?>
					</div>
				</div>
				<div class="stm_lms_question_image_match__answer answer_<?php echo esc_attr( $question_id ); ?> empty"></div>
			</div>
		<?php } ?>
		<input type="text" class="stm_lms_question_image_match__input" name="<?php echo esc_attr( $question_id ); ?>"/>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h4 class="stm_lms_question_image_match__matches_title">
				<?php esc_html_e( 'Drag and match Answer', 'masterstudy-lms-learning-management-system' ); ?>
			</h4>
			<div class="stm_lms_question_image_match__matches">
				<?php
				shuffle( $answers );
				foreach ( $answers as $answer ) {
					?>
					<div class="stm_lms_question_image_match__container container_<?php echo esc_attr( $question_id ); ?>">
						<div class="stm_lms_question_image_match__match ui-state-highlight <?php echo ! empty( $question_view_type ) ? esc_attr( $question_view_type ) : ''; ?>"
								data-answer="<?php echo esc_attr( $answer['text'] ); ?>"
								data-url="<?php echo ( ! empty( $answer['text_image']['url'] ) ) ? esc_url( $answer['text_image']['url'] ) : ''; ?>">
							<div class="image_match_answer">
								<div class="image_box <?php echo empty( $answer['text_image']['url'] ) ? 'empty' : ''; ?>">
									<?php if ( ! empty( $answer['text_image']['url'] ) ) { ?>
										<img src="<?php echo esc_url( $answer['text_image']['url'] ); ?>"/>
									<?php } ?>
								</div>
								<?php if ( ! empty( $answer['text'] ) ) { ?>
									<span><?php echo wp_kses_post( $answer['text'] ); ?></span>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

</div>
