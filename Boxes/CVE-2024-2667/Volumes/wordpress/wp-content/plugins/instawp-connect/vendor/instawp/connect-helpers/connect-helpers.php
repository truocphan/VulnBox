<?php
/**
 * InstaWP Connect Helpers
 *
 * @package      InstaWP\Connect\Helpers
 * @copyright    Copyright (C) 2023, InstaWP
 * @link         http://instawp.com
 * @since        1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       InstaWP Connect Helpers
 * Version:           1.0.0
 * Plugin URI:        https://instawp.com
 * Description:       Helpers Package for InstaWP Remote Features.
 * Author:            InstaWP
 * Author URI:        https://instawp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.6
 * Tested up to:      6.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Autoloading.
 */
$path = dirname( __FILE__ ) . '/vendor/autoload.php';
if ( file_exists( $path ) ) {
    include $path;
} else {
    add_action( 'admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p><?php _e( 'Please run <code>composer install</code> to use InstaWP Connect Helpers Package as a plugin.', 'connect-helpers' ); ?></p>
        </div>
        <?php
    } );
}