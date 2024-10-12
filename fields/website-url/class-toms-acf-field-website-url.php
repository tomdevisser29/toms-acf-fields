<?php

defined('ABSPATH') or die;

class toms_acf_field_website_url extends \acf_field {
    /**
     * Controls field type visibility in REST requests.
     * @var bool
     */
    public $show_in_rest = true;

    /**
     * Environmment values realting to the theme or plugin.
     * @var array $env Plugin or theme context such as 'url' and 'version'
     */
    private $env;

    /**
     * Initialization of the Website URL field.
     */
    public function __construct() {
        /**
         * Field type reference used in PHP and JS code.
         */
        $this->name = 'website_url';

        /**
         * Field type label, for the public facing UI.
         */
        $this->label = __('Website URL', 'toms');

        /**
         * The category the field appears within in the field type picker.
         */
        $this->category = 'basic';

        /**
         * Field type description.
         */
        $this->description = __('Use this field when you need a URL field that automatically adds a safe "https://" prefix, protecting you from using unsafe links and making it easier to use than the default URL field type.', 'toms');

        $this->env = array(
            'url' => site_url(str_replace(ABSPATH, '', __DIR__)),
            'version' => TAF_VERSION,
        );

        add_filter('acf/update_value/type=website_url', array($this, 'remove_url_protocol'), 10, 1);

        parent::__construct();
    }

    /**
     * Settings to display when users configure a field of this type.
     * @param array $field
     * @return void
     */
    public function render_field_settings($field) {
    }

    /**
     * Removes the URL protocol.
     * @param string $value
     * @return string
     */
    public function remove_url_protocol($value) {
        if (is_string($value)) {
            $value = str_replace('https://', '',  $value);
            $value = str_replace('http://', '',  $value);
        }
        return $value;
    }

    /**
     * HTML content to show when a publisher edits the field on the edit screen.
     * @param array $field
     * @return void
     */
    public function render_field($field) {
        echo '<div class="input-group">';
        echo '<span>https://</span>';
        echo '<input id="' . esc_attr($field['id']) . '" type="text" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
        echo '</div>';
    }

    /**
     * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
     * @return void
     */
    public function input_admin_enqueue_scripts() {
        $url = $this->env['url'];
        $version = $this->env['version'];

        wp_register_script(
            'taf-website-url',
            "{$url}/assets/field.js",
            array('acf-input'),
            $version
        );

        wp_register_style(
            'taf-website-url',
            "{$url}/assets/field.css",
            array('acf-input'),
            $version
        );

        wp_enqueue_script('taf-website-url');
        wp_enqueue_style('taf-website-url');
    }
}
