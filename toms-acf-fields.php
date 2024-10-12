<?php

/**
 * Plugin Name: Tom's ACF Fields
 * Author: Tom de Visser
 * Version: 1.0.0
 * Description: Some extra ACF Field Types that are not available in the base plug-in.
 */

defined('ABSPATH') or die;

define('TAF_VERSION', '1.0.0');
define('TAF_DIR', __DIR__);
define('FIELDS_DIR', TAF_DIR . '/fields');

if (! function_exists('acf_register_field_type')) {
    wp_die("Tom's ACF Fields needs to have ACF installed and activated to work.");
}

require_once FIELDS_DIR . '/website-url/init.php';
require_once FIELDS_DIR . '/phone-number/init.php';
