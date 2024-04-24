<?php
/**
 * Masteriyo library modal open button template.
 *
 * @since 1.6.12
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="masteriyo-templates-button" tab-index="0">
	<?php masteriyo_get_svg( 'logo', true ); ?>
</div>
<style>
	.masteriyo-templates-button {
		display: flex;
		align-items: center;
		padding: 0 12px;
		background: whitesmoke;
		border-radius: 100px;
		cursor: pointer;
	}
	.masteriyo-templates-button svg {
		width: 18px;
		height: 18px;
	}
</style>
<?php
