<?php
/**
 * The Template for displaying see more reviews button in single course page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/see-more-reviews-button.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.5.9
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<button class="masteriyo-load-more masteriyo-load-more-course-review">
	<?php esc_html_e( 'See more reviews', 'masteriyo' ); ?>
</button>
<?php
