<?php
/**
 * Migrate template - Management
 */

?>

<div class="nav-item-content manage bg-white rounded-md p-6">
    <form class="instawp-form w-full" onsubmit="return false;">
        <div class="instawp-form-fields">
			<?php foreach ( array_values( InstaWP_Setting::get_management_settings() ) as $index => $section ) : ?>
				<?php InstaWP_Setting::generate_section( $section, $index ); ?>
			<?php endforeach; ?>
        </div>
    </form>
</div>