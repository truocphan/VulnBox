<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/email-header.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo wp_kses_post( get_bloginfo( 'name', 'display' ) ); ?></title>
	</head>
	<body>
		<div id="content" class="email-template">
