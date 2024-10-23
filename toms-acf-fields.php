<?php
/**
 * Plugin Name: dev5 ACF Fields
 * Author: Tom de Visser
 * Version: 1.0.0
 * Description: Some extra ACF Field Types that are not available in the base plug-in.
 *
 * @package dev5
 */

defined( 'ABSPATH' ) || die;

define( 'DEV5_VERSION', '1.0.0' );
define( 'DEV5_DIR', __DIR__ );
define( 'DEV5_FIELDS_DIR', DEV5_DIR . '/fields' );

if ( ! function_exists( 'acf_register_field_type' ) ) {
	wp_die( 'dev5 ACF Fields needs to have ACF installed and activated to work.' );
}

require_once DEV5_FIELDS_DIR . '/website-url/init.php';
require_once DEV5_FIELDS_DIR . '/phone-number/init.php';
