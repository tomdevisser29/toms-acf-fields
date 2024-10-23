<?php
/**
 * Initializes the custom website url field.
 *
 * @package dev5
 */

defined( 'ABSPATH' ) || die;

/**
 * Register the ACF field type.
 *
 * @return void
 */
function dev5_register_website_url_field_type() {
	require_once DEV5_FIELDS_DIR . '/website-url/class-dev5-acf-field-website-url.php';
	acf_register_field_type( 'Dev5_Acf_Field_Website_Url' );
}
add_action( 'init', 'dev5_register_website_url_field_type' );
