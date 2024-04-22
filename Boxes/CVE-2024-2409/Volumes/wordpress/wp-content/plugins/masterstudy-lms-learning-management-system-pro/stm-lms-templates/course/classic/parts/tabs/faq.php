<?php
stm_lms_register_style( 'faq' );

$faq = get_post_meta( get_the_ID(), 'faq', true );

if ( ! empty( $faq ) ) :
	$faq = json_decode( $faq, true );

	if ( ! empty( $faq ) ) :
		?>
		<div class="panel-group" id="stm_lms_faq" role="tablist" aria-multiselectable="true">
			<?php
			foreach ( $faq as $faq_id => $faq_doc ) :
				if ( empty( $faq_doc['answer'] ) || empty( $faq_doc['question'] ) ) {
					continue;
				}
				?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading<?php echo esc_attr( $faq_id ); ?>">
						<h4 class="panel-title">
							<a role="button"
								data-toggle="collapse"
								class="heading_font collapsed"
								data-parent="#accordion"
								href="#collapse<?php echo esc_attr( $faq_id ); ?>"
								aria-expanded="true"
								aria-controls="collapse<?php echo esc_attr( $faq_id ); ?>">
								<i class="fa fa-angle-down"></i>
								<?php echo $faq_doc['question']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</h4>
					</div>
					<div id="collapse<?php echo esc_attr( $faq_id ); ?>"
							class="panel-collapse collapse"
							role="tabpanel"
							aria-labelledby="heading<?php echo esc_attr( $faq_id ); ?>">
						<div class="panel-body">
							<?php echo html_entity_decode( $faq_doc['answer'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
endif;
