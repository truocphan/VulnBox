<?php
/**
 * Info Notice.
 *
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="masteriyo-notify-message masteriyo-alert masteriyo-info-msg">
	<span><?php echo wp_kses_post( $message ); ?></span>
</div>

<?php
