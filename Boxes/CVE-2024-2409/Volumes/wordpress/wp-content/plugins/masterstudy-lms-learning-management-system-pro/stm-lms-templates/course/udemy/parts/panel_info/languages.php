<?php
/**
 * @var $udemy_meta
 */

if ( ! empty( $udemy_meta['udemy_caption_languages'] ) && ! empty( $udemy_meta['udemy_caption_languages'][0] ) ) :
	$langs = $udemy_meta['udemy_caption_languages'];
	if ( count( $langs ) > 1 ) {
		$title  = implode( ', ', $langs );
		$toggle = "data-toggle='tooltip' data-placement='bottom' title='{$title}'";
	}
	?>
	<div class="stm_lms_udemy_caption_languages">
		<i class="fas fa-language"></i>
		<span><?php echo $udemy_meta['udemy_caption_languages'][0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php if ( ! empty( $toggle ) ) : ?>
			<span class="more_langs" <?php echo wp_kses_post( $toggle ); ?>>
				<?php esc_html_e( 'More', 'masterstudy-lms-learning-management-system-pro' ); ?>
				<i class="lnr lnr-arrow-right"></i>
			</span>
		<?php endif; ?>
	</div>

<?php endif; ?>
