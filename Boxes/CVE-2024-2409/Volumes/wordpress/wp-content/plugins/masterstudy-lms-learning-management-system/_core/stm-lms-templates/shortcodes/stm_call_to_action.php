<?php
$heading_custom_attributes = ( ! empty( $heading_link['custom_attributes'] ) ) ? \Elementor\Utils::parse_custom_attributes( $heading_link['custom_attributes'] ) : '';
$button_custom_attributes  = ( ! empty( $button_link['custom_attributes'] ) ) ? \Elementor\Utils::parse_custom_attributes( $button_link['custom_attributes'] ) : '';
?>
<div class="stm_lms_cta">
	<div class="stm_lms_cta__container">
		<span class="stm_lms_cta__icon">
			<?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
		</span>
	</div>
	<div class="stm_lms_cta__container">
		<a href="<?php echo esc_attr( $heading_link['url'] ); ?>" class="stm_lms_cta__heading_link"
		<?php
		echo ( ! empty( $heading_link['nofollow'] ) ) ? 'rel="nofollow"' : '';
		echo ( ! empty( $heading_link['is_external'] ) ) ? 'target="_blank"' : '';
		if ( is_array( $heading_custom_attributes ) ) {
			foreach ( $heading_custom_attributes as $key => $value ) {
				echo esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
		?>
		>
			<h2 class="stm_lms_cta__heading">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</a>
		<div class="stm_lms_cta__text">
			<?php echo esc_html( $text ); ?>
		</div>
		<a href="<?php echo esc_attr( $button_link['url'] ); ?>" class="stm_lms_cta__button" 
		<?php
		echo ( ! empty( $button_link['nofollow'] ) ) ? 'rel="nofollow"' : '';
		echo ( ! empty( $button_link['is_external'] ) ) ? 'target="_blank"' : '';
		if ( is_array( $button_custom_attributes ) ) {
			foreach ( $button_custom_attributes as $key => $value ) {
				echo esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
		?>
		>
			<?php echo esc_html( $button_text ); ?>
		</a>
	</div>
</div>
