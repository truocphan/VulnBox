<?php
/**
 * Masteriyo login form template.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering login form template.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_login_form' );

masteriyo_get_template_part( 'content', 'login-form' );

/**
 * Fires after rendering login form template.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_login_form' );

