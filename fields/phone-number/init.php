<?php
/**
 * Initializes the custom phone number field.
 *
 * @package dev5
 */

defined( 'ABSPATH' ) || die;

/**
 * Register the ACF field type.
 *
 * @return void
 */
function dev5_register_phone_number_field_type(): void {
	require_once DEV5_FIELDS_DIR . '/phone-number/class-dev5-acf-field-phone-number.php';
	acf_register_field_type( 'Dev5_Acf_Field_Phone_Number' );
}
add_action( 'init', 'dev5_register_phone_number_field_type' );
