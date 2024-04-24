<?php
/**
 * The Template for displaying instructors list page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/instructors-list.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.7.0
 */

masteriyo_custom_header( 'instructors-list' );

/**
 * Wrapper div opening.
 *
 * @since 1.6.16
 */
echo '<div class="masteriyo-w-100 masteriyo-container">';

/**
 * Fires before rendering header in instructors list template.
 *
 * @since 1.6.16
 */
do_action( 'masteriyo_before_instructors_list_header' );

?>
<header class="masteriyo-instructors-list-header">
	<?php
	/**
	 * Filters boolean: true if page title should be shown.
	 *
	 * @since 1.6.16
	 *
	 * @param boolean $bool true if page title should be shown.
	 */
	if ( apply_filters( 'masteriyo_show_page_title', true ) ) :
		?>
		<h1 class="masteriyo-instructors-list-header__title page-title">
			<?php masteriyo_page_title(); ?>
		</h1>
	<?php endif; ?>

	<?php
	/**
	 * Action hook for rendering description in instructors list.
	 *
	 * @since 1.6.16
	 */
	do_action( 'masteriyo_instructors_list_description' );
	?>
</header>

<?php
/**
 * Fires after rendering header in instructors list template.
 *
 * @since 1.6.16
 */
do_action( 'masteriyo_after_instructors_list_header' );
?>

<?php
/**
 * Fires for rendering main content in instructors list.
 *
 * @since 1.6.16
 */
do_action( 'masteriyo_instructors_list_main_content' );

/**
 * Wrapper div closing.
 *
 * @since 1.6.16
 */
echo '</div>';

masteriyo_custom_footer( 'instructors-list' );
