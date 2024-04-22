<?php

/**
 * @var $lesson_type
 */


switch($lesson_type) {
	case ('video') : ?>
        <div class="container">
        <div class="row">
		<div class="col-md-12">
		<?php break;
    case ('slide') : ?>
        <div class="container">
        <div class="row">
        <div class="col-md-12">
        <?php break;
	default: ?>
        <div class="container">
        <div class="row">
		<div class="col-md-8 col-md-push-2">
<?php }