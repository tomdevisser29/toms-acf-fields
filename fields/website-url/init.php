<?php

defined('ABSPATH') or die;

add_action('init', 'toms_register_website_url_field_type');
function toms_register_website_url_field_type() {
    require_once FIELDS_DIR . '/website-url/class-toms-acf-field-website-url.php';
    acf_register_field_type('toms_acf_field_website_url');
}
