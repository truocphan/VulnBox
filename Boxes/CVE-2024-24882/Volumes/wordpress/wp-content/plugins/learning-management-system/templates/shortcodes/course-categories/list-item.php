<?php
/**
 * The Template for displaying course categories list item.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/shortcodes/course-categories/list-item.php
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="masteriyo-col">
	<div class="masteriyo-category-card">
		<a href="<?php echo esc_attr( $category->get_permalink() ); ?>">
			<div class="masteriyo-category-card__image"><?php echo wp_kses_post( $category->get_image() ); ?></div>
		</a>
		<div class="masteriyo-category-card__detail">
			<h2 class="masteriyo-category-card__title">
				<a href="<?php echo esc_attr( $category->get_permalink() ); ?>"><?php echo esc_html( $category->get_name() ); ?></a>
			</h2>
			<?php if ( 'yes' !== $hide_courses_count ) : ?>
				<div class="masteriyo-category-card__courses">
					<span>
						<?php
						/* translators: 1: Count of courses in a category */
						echo esc_html( sprintf( '%s Courses', $category->get_count() ) );
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
