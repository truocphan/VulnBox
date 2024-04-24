<?php
/**
 * Special Divi integration file. This file is automatically loaded by Divi.
 * This file must be located in the same directory as the MasteriyoDiviExtension class which
 * extends the DiviExtension class. This file is used to load integration
 * files like Divi modules etc.
 *
 * @since 1.6.13
 */

use Masteriyo\Addons\DiviIntegration\Modules\CourseCategoriesModule;
use Masteriyo\Addons\DiviIntegration\Modules\CourseListModule;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ET_Builder_Module' ) ) {
	return;
}

new CourseListModule();
new CourseCategoriesModule();
