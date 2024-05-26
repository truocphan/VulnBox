<?php

function hashform_sanitize_checkbox($input) {
    if ($input == 'on') {
        return 'on';
    } else {
        return 'off';
    }
}

function hashform_sanitize_number($input) {
    if (is_numeric($input)) {
        return intval($input);
    } else {
        return '';
    }
}

function hashform_sanitize_float($input) {
    if (is_numeric($input)) {
        return (float) $input;
    } else {
        return '';
    }
}

function hashform_sanitize_color($color) {
    // Is this an rgba color or a hex?
    $mode = ( false === strpos($color, 'rgba') ) ? 'hex' : 'rgba';
    if ('rgba' === $mode) {
        $color = str_replace(' ', '', $color);
        sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
        return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
    } else {
        return sanitize_hex_color($color);
    }
}

function hashform_sanitize_url($url) {
    $sanitized_url = strip_tags(stripslashes(filter_var($url, FILTER_VALIDATE_URL)));
    return $sanitized_url;
}

function hashform_sanitize_checkbox_boolean($input) {
    if (true == $input) {
        return true;
    } else {
        return false;
    }
}

function hashform_sanitize_allowed_file_extensions($extensions) {
    $new_extensions = array();
    $extensions = explode(',', $extensions);
    $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ppt', 'pptx', 'pps', 'ppsx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'mp3', 'mp4', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'avi', 'mpg', 'ogv', '3gp', 'txt', 'zip', 'rar', '7z', 'csv');
    foreach ($extensions as $row) {
        $extension = trim($row);
        if (in_array($extension, $allowed_extensions)) {
            $new_extensions[] = $extension;
        }
    }
    return implode(',', $new_extensions);
}
