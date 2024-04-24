<?php
/**
 * The template for displaying account.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering account page content.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_account_content' );

masteriyo_display_all_notices();

?>
<div id="masteriyo-account-page">

</div>

<?php

/**
 * Fires after rendering account page content.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_account_content' );
