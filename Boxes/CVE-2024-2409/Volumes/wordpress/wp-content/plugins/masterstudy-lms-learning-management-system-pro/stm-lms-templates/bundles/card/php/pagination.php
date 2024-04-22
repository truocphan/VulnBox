<?php
/**
 * @var $pages
 */

$i    = 0;
$page = get_query_var( 'paged', 1 );

if ( empty( $page ) ) {
	$page = 1;
}
$page_link = get_the_permalink();

if ( $pages > 1 ) : ?>
	<ul class="page-numbers stm_lms_my_course_bundles__pagination">
		<?php
		while ( $i < $pages ) :
			$i++;
			?>
			<li>
				<?php if ( $page !== $i ) : ?>
					<a class="page-numbers" href="<?php echo esc_url( add_query_arg( array( 'paged' => $i ), $page_link ) ); ?>">
						<?php echo intval( $i ); ?>
					</a>
				<?php else : ?>
					<span class="page-numbers current"><?php echo intval( $i ); ?></span>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php
endif;
