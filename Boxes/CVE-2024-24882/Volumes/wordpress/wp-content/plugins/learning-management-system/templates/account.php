<?php
/**
 * Masteriyo account page template.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering account page template.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_account' );

masteriyo_get_template_part( 'content', 'account' );

/**
 * Fires after rendering account page template.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_account' );

