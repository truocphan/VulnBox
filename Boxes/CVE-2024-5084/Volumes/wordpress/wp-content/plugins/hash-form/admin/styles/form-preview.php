<?php

defined('ABSPATH') || die();

HashFormPreview::show_form($form_id);

echo '<style>';
echo '#hf-container-00{';
self::get_style_vars($hashform_styles, '');
echo '}';
echo '</style>';
