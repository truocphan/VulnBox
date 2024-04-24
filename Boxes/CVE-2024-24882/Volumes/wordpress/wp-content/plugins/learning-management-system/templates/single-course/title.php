<?php
/**
 * The Template for displaying course title in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/title.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<h1 class="masteriyo-single-course--title"><?php echo esc_html( $course->get_title() ); ?></h1> 
