<?php

defined('ABSPATH') or die;

add_action('init', 'toms_register_website_url_field_type');
function toms_register_website_url_field_type() {
    require_once FIELDS_DIR . '/website-url/class-toms-acf-field-website-url.php';
    acf_register_field_type('toms_acf_field_website_url');
}

add_filter('acf/update_value/type=website_url', 'toms_remove_url_protocol', 10, 4);
function toms_remove_url_protocol($value, $post_id, $field, $original) {
    if (is_string($value)) {
        $value = str_replace('https://', '',  $value);
        $value = str_replace('http://', '',  $value);
    }
    return $value;
}
