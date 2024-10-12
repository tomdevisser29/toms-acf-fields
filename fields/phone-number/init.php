<?php

defined('ABSPATH') or die;

add_action('init', 'toms_register_phone_number_field_type');
function toms_register_phone_number_field_type() {
    require_once FIELDS_DIR . '/phone-number/class-toms-acf-field-phone-number.php';
    acf_register_field_type('toms_acf_field_phone_number');
}
