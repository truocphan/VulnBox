<?php

/**
 * The Template for displaying instructors list item.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/shortcodes/instructors-list/list-item.php
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

defined( 'ABSPATH' ) || exit;

?>
<div class="masteriyo-col">
	<div class="masteriyo-instructor-card">
		<a href="<?php echo esc_url( $instructor['url'] ); ?>" class="masteriyo-instructor-card__image-wrapper">
			<div class="masteriyo-instructor-card__image">
				<img src="<?php echo esc_url( $instructor['profile_image_url'] ); ?>" alt="<?php echo esc_attr( $instructor['full_name'] ); ?>" srcset="">
			</div>
		</a>
		<div class="masteriyo-instructor-card__detail">
			<h2 class="masteriyo-instructor-card__title">
				<a href="<?php echo esc_url( $instructor['url'] ); ?>">
					<?php
					echo esc_html( $instructor['full_name'] );
					?>
				</a>
			</h2>
			<p class="masteriyo-instructor-card__info">
				<span title="<?php esc_attr_e( 'Username', 'masteriyo' ); ?>" class="username">
					<?php
					masteriyo_get_svg( 'user', true );
					echo esc_html( $instructor['username'] );
					?>
				</span>
				<span title="<?php esc_attr_e( 'Email', 'masteriyo' ); ?>" class="email">
					<?php
					masteriyo_get_svg( 'email', true );
					echo esc_html( $instructor['email'] );
					?>
				</span>
				<span title="<?php esc_attr_e( 'Joined Date', 'masteriyo' ); ?>" class="joined-date">
					<?php
					masteriyo_get_svg( 'joined_date', true );
					echo esc_html( $instructor['date_created'] );
					?>
				</span>
				<span title="<?php esc_attr_e( 'Courses Count', 'masteriyo' ); ?>" class="courses-count">
					<?php
					masteriyo_get_svg( 'courses_count', true );
					echo esc_html( $instructor['courses_count'] );
					?>
				</span>
				<span title="<?php esc_attr_e( 'Students Count', 'masteriyo' ); ?>" class="students-count">
					<?php
					masteriyo_get_svg( 'students_count', true );
					echo esc_html( $instructor['students_count'] );
					?>
				</span>
			</p>
			<p class="masteriyo-instructor-card__description">
				<?php echo esc_textarea( $instructor['description'] ); ?>
			</p>
		</div>
	</div>
</div>
<?php
