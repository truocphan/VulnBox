<?php
/**
 * Learn page.
 */
?>

<!DOCTYPE html>
<html lang="en" <?php echo(is_rtl() ?  'dir="rtl"' : ''); ?> >
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?php the_title(); ?></title>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> translate="no">
		<div id="masteriyo-interactive-course"></div>
		<?php wp_footer(); ?>
	</body>
</html>

<?php

