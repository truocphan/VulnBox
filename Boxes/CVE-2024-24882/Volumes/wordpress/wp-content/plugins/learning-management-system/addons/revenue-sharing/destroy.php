<?php
/**
 * Destroy/tear down revenue sharing addon.
 *
 * @since 1.6.14
 */

wp_roles()->remove_cap( 'administrator', 'manage_withdraws' );
