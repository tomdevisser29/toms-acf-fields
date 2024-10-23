<?php
/**
 * Defines the class that registers the phone number field.
 *
 * @package dev5
 */

/**
 * Extends acf_field to create a phone number field with validation and country codes.
 */
class Dev5_Acf_Field_Phone_Number extends \acf_field {
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
		$this->name = 'phone_number';

		/**
		 * Field type label, for the public facing UI.
		 */
		$this->label = __( 'Phone number', 'dev5' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic';

		/**
		 * Field type description.
		 */
		$this->description = __( 'Use this field when you need phone number validation. It will save both a visually appealing phone number and a link-friendly number stripped of all characters.', 'dev5' );

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ),
			'version' => DEV5_VERSION,
		);

		add_filter( 'acf/validate_value/type=phone_number', array( $this, 'validate_value' ), 10, 3 );

		parent::__construct();
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field array containing all settings.
	 * @return void
	 */
	public function render_field( array $field ) {
		$number  = ! empty( $field['value']['number'] ) ? $field['value']['number'] : '';
		$country = ! empty( $field['value']['country'] ) ? $field['value']['country'] : '';

		echo '<div class="input-group">';
		echo '<select name="' . esc_attr( $field['name'] ) . '[country]">';
		echo '<option value="us"' . selected( $country, 'us', false ) . '>US (+1)</option>';
		echo '<option value="nl"' . selected( $country, 'nl', false ) . '>NL (+31)</option>';
		echo '<option value="be"' . selected( $country, 'be', false ) . '>BE (+32)</option>';
		echo '<option value="de"' . selected( $country, 'de', false ) . '>DE (+49)</option>';
		echo '</select>';
		echo '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $field['name'] ) . '[number]" value="' . esc_attr( $number ) . '">';
		echo '</div>';
	}

	/**
	 * Update field value before saving to the database.
	 *
	 * @param mixed $value The value that is being updated.
	 * @return mixed
	 */
	public function update_value( mixed $value ): mixed {
		$country        = isset( $value['country'] ) ? $value['country'] : '';
		$number         = isset( $value['number'] ) ? $value['number'] : '';
		$prefix         = $this->get_country_prefix( $country );
		$stripped       = preg_replace( '/\D/', '', $number );
		$display_number = $this->format_phone_number_for_display( $prefix, $country, $stripped );

		return array(
			'country'  => $country,
			'number'   => $number,
			'display'  => $display_number,
			'stripped' => $stripped,
			'prefix'   => $prefix,
			'tel'      => $prefix . $stripped,
		);
	}

	/**
	 * Apply country-specific phone number formatting, to consistently display phone numbers on your frontend.
	 *
	 * @param string $prefix The country specific prefix.
	 * @param string $country The country for the phone number.
	 * @param string $stripped The stripped phone number.
	 * @return string
	 */
	private function format_phone_number_for_display( string $prefix, string $country, string $stripped ): string {
		switch ( $country ) {
			case 'nl': // Netherlands
				// Format: +31 6 12345678 (Mobile) or +31 20 1234567 (Landline).
				if ( '6' === substr( $stripped, 0, 1 ) ) {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 1 ) . ' ' . substr( $stripped, 1, 4 ) . ' ' . substr( $stripped, 5 );
				} else {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 2 ) . ' ' . substr( $stripped, 2, 3 ) . ' ' . substr( $stripped, 5 );
				}
				break;

			case 'be': // Belgium
				// Format: +32 4XX XX XX XX (Mobile) or +32 2 XXX XX XX (Landline).
				if ( '4' === substr( $stripped, 0, 1 ) ) {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 3 ) . ' ' . substr( $stripped, 3, 2 ) . ' ' . substr( $stripped, 5, 2 ) . ' ' . substr( $stripped, 7 );
				} else {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 1 ) . ' ' . substr( $stripped, 1, 3 ) . ' ' . substr( $stripped, 4, 2 ) . ' ' . substr( $stripped, 6 );
				}
				break;

			case 'de': // Germany
				// Format: +49 1512 3456789 (Mobile) or +49 30 123456 (Landline).
				if ( '15' === substr( $stripped, 0, 2 ) || '16' === substr( $stripped, 0, 2 ) ) {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 4 ) . ' ' . substr( $stripped, 4 );
				} else {
					$formatted_number = $prefix . ' ' . substr( $stripped, 0, 2 ) . ' ' . substr( $stripped, 2, 3 ) . ' ' . substr( $stripped, 5 );
				}
				break;

			case 'us': // United States
				// Format: +1 (XXX) XXX-XXXX.
				$formatted_number = $prefix . ' (' . substr( $stripped, 0, 3 ) . ') ' . substr( $stripped, 3, 3 ) . '-' . substr( $stripped, 6 );
				break;

			default:
				$formatted_number = $prefix . ' ' . $stripped;
				break;
		}

		return $formatted_number;
	}

	/**
	 * Validation logic for phone numbers based on the selected country.
	 *
	 * @param bool  $valid Whether the value is valid (true) or not (false).
	 * @param mixed $value The field value.
	 * @param array $field The field array holding all the field settings.
	 * @return bool|string
	 */
	public function validate_value( bool $valid, mixed $value, array $field ): bool|string {
		$country = isset( $value['country'] ) ? $value['country'] : '';
		$number  = isset( $value['number'] ) ? preg_replace( '/\D/', '', $value['number'] ) : '';

		$digit_lengths = array(
			'nl' => 9,
			'be' => 9,
			'de' => array( 10, 11 ),
			'us' => 10,
		);

		if ( isset( $digit_lengths[ $country ] ) ) {
			$valid_lengths = (array) $digit_lengths[ $country ];

			if ( ! in_array( strlen( $number ), $valid_lengths, true ) ) {
				if ( 1 !== $field['required'] && 0 === strlen( $number ) ) {
					return $valid;
				}

				$valid = __( 'The phone number you entered has an invalid length.', 'dev5' );
			}
		}

		return $valid;
	}

	/**
	 * Returns the country phone prefix.
	 *
	 * @param string $country The country for which to get a prefix.
	 * @return string $prefix
	 */
	public function get_country_prefix( string $country ): string {
		if ( ! empty( $country ) ) {
			switch ( $country ) {
				case 'nl':
					$prefix = '+31';
					break;
				case 'be':
					$prefix = '+32';
					break;
				case 'de':
					$prefix = '+49';
					break;
				case 'us':
					$prefix = '+1';
					break;
				default:
					$prefix = '';
			}
		}

		return $prefix;
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
			'DEV5-phone-number',
			"{$url}/assets/field.js",
			array( 'acf-input' ),
			$version,
			false
		);

		wp_register_style(
			'DEV5-phone-number',
			"{$url}/assets/field.css",
			array( 'acf-input' ),
			$version
		);

		wp_enqueue_script( 'DEV5-phone-number' );
		wp_enqueue_style( 'DEV5-phone-number' );
	}
}
