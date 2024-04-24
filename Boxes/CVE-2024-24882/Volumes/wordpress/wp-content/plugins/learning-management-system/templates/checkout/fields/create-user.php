<?php
/**
 * The Template for displaying checkout form field.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/fields/create-user.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *w
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

use Masteriyo\Notice;
?>

<div class="masteriyo-checkout---create-user-wrapper">
	<div class="masteriyo-checkout----create-user">
	<input
		type="checkbox"
		id="create-new-user"
		name="create_user"
	/>
		<label for="create-new-user" class="masteriyo-label">
			<?php esc_html_e( 'Create User', 'masteriyo' ); ?>
			<span>*</span>
		</label>
  </div>

	<?php if ( masteriyo_notice_exists( 'create_user', Notice::ERROR ) ) : ?>
		<div class="masteriyo-error masteriyo-danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'create_user', Notice::ERROR ) ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
