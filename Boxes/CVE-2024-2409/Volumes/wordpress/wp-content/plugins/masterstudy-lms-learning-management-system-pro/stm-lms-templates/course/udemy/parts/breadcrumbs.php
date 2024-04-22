<?php if ( function_exists( 'bcn_display' ) ) : ?>
	</div>

	<div class="stm_breadcrumbs_unit">
		<div class="container">
			<div class="navxtBreads">
				<?php bcn_display(); ?>
			</div>
		</div>
	</div>

	<div class="container">

<?php else : ?>
	<div class="stm_breadcrumbs_unit"></div>
	<?php
endif;
