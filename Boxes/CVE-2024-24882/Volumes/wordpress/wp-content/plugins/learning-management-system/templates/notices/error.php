<?php
/**
 * Error Notice.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="masteriyo-notify-message masteriyo-alert masteriyo-danger-msg">
	<span><?php echo wp_kses_post( $message ); ?></span>
</div>

<?php
