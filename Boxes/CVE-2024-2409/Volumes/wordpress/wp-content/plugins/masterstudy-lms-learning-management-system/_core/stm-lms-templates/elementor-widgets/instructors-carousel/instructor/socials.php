<div class="ms_lms_instructors_carousel__item_socials <?php echo ( ! empty( $socials_presets ) ) ? esc_attr( $socials_presets ) : 'style_1'; ?>">
	<?php if ( ! empty( $user['meta']['facebook'] ) ) { ?>
		<a href="<?php echo esc_url( $user['meta']['facebook'] ); ?>" class="ms_lms_instructors_carousel__item_socials_link">
			<i class="fab fa-facebook-f"></i>
		</a>
	<?php } ?>
	<?php if ( ! empty( $user['meta']['instagram'] ) ) { ?>
		<a href="<?php echo esc_url( $user['meta']['instagram'] ); ?>" class="ms_lms_instructors_carousel__item_socials_link">
			<i class="fab fa-instagram"></i>
		</a>
	<?php } ?>
	<?php if ( ! empty( $user['meta']['twitter'] ) ) { ?>
		<a href="<?php echo esc_url( $user['meta']['twitter'] ); ?>" class="ms_lms_instructors_carousel__item_socials_link">
			<i class="fab fa-twitter"></i>
		</a>
	<?php } ?>
	<?php if ( ! empty( $user['meta']['google-plus'] ) ) { ?>
		<a href="<?php echo esc_url( $user['meta']['google-plus'] ); ?>" class="ms_lms_instructors_carousel__item_socials_link">
			<i class="fab fa-google-plus-g"></i>
		</a>
	<?php } ?>
</div>
