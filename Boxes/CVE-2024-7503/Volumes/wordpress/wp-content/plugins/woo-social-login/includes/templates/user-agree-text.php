<?php
/**
 * Login with email
 * 
 * Handles to load Login in with email template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/user-agree-text.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.8.3
 */
?>

<!-- beginning of the privacy policy text container -->
<div class="wooslg-user-agree-text">
	<p>
		<input type="checkbox" name="wooslg-user-agree-check" class="wooslg-user-agree-check">
		<?php echo $useragree_text;?>
	</p>
</div>