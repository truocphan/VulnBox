<?php
/**
 * @var $udemy_meta
 */

if ( ! empty( $udemy_meta['udemy_headline'] ) ) : ?>
	<div class="stm_lms_udemy_headline">
		<?php echo wp_kses_post( $udemy_meta['udemy_headline'] ); ?>
	</div>
<?php else : ?>
	<div class="stm_lms_udemy_headline">
		<?php the_excerpt(); ?>
	</div>
	<?php
endif;
