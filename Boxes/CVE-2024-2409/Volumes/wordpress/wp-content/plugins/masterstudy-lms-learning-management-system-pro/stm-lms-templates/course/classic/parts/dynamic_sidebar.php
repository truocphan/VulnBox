<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_active_sidebar( 'stm_lms_sidebar' ) ) :
	stm_lms_register_style( 'dynamic_sidebar' );
	?>
	<div class="stm-lms-dynamic_sidebar">
		<div class="multiseparator"></div>
		<?php dynamic_sidebar( 'stm_lms_sidebar' ); ?>
	</div>
	<?php
endif;
