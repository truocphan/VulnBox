<?php
/**
 * The Template for displaying course featured image in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/featured-image.php.
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

<div class="masteriyo-course--img-wrap">
	<?php if ( $difficulty ) : ?>
		<div class="difficulty-badge <?php echo esc_attr( $difficulty['slug'] ); ?>" data-id="<?php echo esc_attr( $difficulty['id'] ); ?>">
			<?php if ( $difficulty['color'] ) : ?>
				<span class="masteriyo-badge" style="background-color: <?php echo esc_attr( $difficulty['color'] ); ?>">
					<?php echo esc_html( $difficulty['name'] ); ?>
				</span>
			<?php else : ?>
				<span class="masteriyo-badge <?php echo esc_attr( masteriyo_get_difficulty_badge_css_class( $difficulty['slug'] ) ); ?>">
					<?php echo esc_html( $difficulty['name'] ); ?>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="masteriyo-feature-img">
		<?php echo wp_kses( $course->get_image( 'masteriyo_single' ), 'masteriyo_image' ); ?>
	</div>
</div>

<?php
