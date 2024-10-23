<?php
/**
 * Defines the class that registers the website url field.
 *
 * @package dev5
 */

/**
 * Extends acf_field to create a website url field.
 */
class Dev5_Acf_Field_Website_Url extends \acf_field {
	/**
	 * Controls field type visibility in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environmment values realting to the theme or plugin.
	 *
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
		$this->label = __( 'Website URL', 'dev5' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic';

		/**
		 * Field type description.
		 */
		$this->description = __( 'Use this field when you need a URL field that automatically adds a safe "https://" prefix, protecting you from using unsafe links and making it easier to use than the default URL field type.', 'dev5' );

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ),
			'version' => DEV5_VERSION,
		);

		add_filter( 'acf/update_value/type=website_url', array( $this, 'remove_url_protocol' ), 10, 1 );
		add_filter( 'acf/format_value/type=website_url', array( $this, 'add_url_protocol' ), 10, 1 );

		parent::__construct();
	}

	/**
	 * Removes the URL protocol.
	 *
	 * @param string $value The full url.
	 * @return string
	 */
	public function remove_url_protocol( string $value ): string {
		if ( is_string( $value ) ) {
			$value = str_replace( 'https://', '', $value );
			$value = str_replace( 'http://', '', $value );
		}
		return $value;
	}

	/**
	 * Adds the URL protocol.
	 *
	 * @param string $value The url without protocol.
	 * @return string
	 */
	public function add_url_protocol( string $value ): string {
		if ( is_string( $value ) ) {
			$value = 'https://' . $value;
		}
		return $value;
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field array containing all settings.
	 * @return void
	 */
	public function render_field( array $field ): void {
		echo '<div class="input-group">';
		echo '<span>https://</span>';
		echo '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $field['value'] ) . '">';
		echo '</div>';
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$url     = $this->env['url'];
		$version = $this->env['version'];

		wp_register_script(
			'DEV5-website-url',
			"{$url}/assets/field.js",
			array( 'acf-input' ),
			$version,
			false
		);

		wp_register_style(
			'DEV5-website-url',
			"{$url}/assets/field.css",
			array( 'acf-input' ),
			$version
		);

		wp_enqueue_script( 'DEV5-website-url' );
		wp_enqueue_style( 'DEV5-website-url' );
	}
}
