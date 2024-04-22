<?php
/**
 * @var $current_user
 */

$socials = array( 'facebook', 'twitter', 'instagram', 'google-plus' );
$fields  = STM_LMS_User::extra_fields();
?>

<div class="stm_lms_user_info_top__socials">
	<?php foreach ( $socials as $social ) { ?>
		<?php if ( ! empty( $current_user['meta'][ $social ] ) ) { ?>
			<a href="<?php echo esc_url( $current_user['meta'][ $social ] ); ?>"
				target="_blank"
				class="<?php echo esc_attr( $social ); ?> stm_lms_update_field__<?php echo esc_attr( $social ); ?>"
			>
				<?php if ( 'twitter' !== $social ) { ?>
					<i class="fab fa-<?php echo esc_attr( $fields[ $social ]['icon'] ); ?>"></i>
				<?php } ?>
			</a>
			<?php
		}
	}
	?>
</div>
