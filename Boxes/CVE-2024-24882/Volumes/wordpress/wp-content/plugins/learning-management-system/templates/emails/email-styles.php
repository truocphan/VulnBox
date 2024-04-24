<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/email-styles.php.
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

.email-template {
	max-width: 600px;
	border: 1px solid #EBECF2;
	border-radius: 4px;
	padding: 10px 30px;
	margin: 0 auto;
	margin-top: 32px;
	background: #fff;
}

.email-template p {
	line-height: 1.5;
}

.email-template--title {
	font-size: 20px;
	font-weight: 600;
	color: #07092F;
}

.email-template--button {
	border-radius: 4px;
	background-color: #78A6FF;
	padding:12px 16px;
	margin: 10px 0px;
	color: #fff;
	text-decoration: none;
	display: inline-block;
}

.email-text--bold {
	font-weight: 700;
}

svg {
	width: 12px;
}


//Order table.
.order_item td {
	vertical-align: middle;
	font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
	word-wrap:break-word;
}

.order-list table {
	border-collapse: collapse;
}

.order-list thead th {
	background-color: #78A6FF;
	color: #fff
}

.order-list tbody tr:nth-child(even) {
	background-color: #EBECF2;
}

.order-list tr th,
.order-list tr td {
	border: 1px solid #EBECF2;
	padding: 8px;
}

.order-list {
	margin-bottom: 40px;
}

.order-list table {
	width: 100%;
	font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
}

.order-list tfoot .masteriyo-price-amount{
	font-weight: 700;
}

.address{
	line-height: 1.5;
}
<?php
