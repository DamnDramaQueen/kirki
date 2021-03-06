<?php
/**
 * Initializes Kirki
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

/**
 * Initialize Kirki
 */
class Kirki_Init {

	/**
	 * Control types.
	 *
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private $control_types = array();

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->set_url();
		add_action( 'after_setup_theme', array( $this, 'set_url' ) );
		add_action( 'customize_update_user_meta', array( $this, 'update_user_meta' ), 10, 2 );
		add_action( 'wp_loaded', array( $this, 'add_to_customizer' ), 1 );
		add_filter( 'kirki/control_types', array( $this, 'default_control_types' ) );
	}

	/**
	 * Properly set the Kirki URL for assets.
	 * Determines if Kirki is installed as a plugin, in a child theme, or a parent theme
	 * and then does some calculations to get the proper URL for its CSS & JS assets.
	 */
	public function set_url() {
		if ( defined( 'ABSPATH' ) ) {
			// Replace path with URL.
			$kirki_url  = str_replace( ABSPATH, '', Kirki::$path );
			Kirki::$url = site_url( $kirki_url );
			// Escape the URL.
			Kirki::$url = esc_url_raw( Kirki::$url );
		}
		// Apply the kirki/config filter.
		$config = apply_filters( 'kirki/config', array() );
		if ( isset( $config['url_path'] ) ) {
			Kirki::$url = esc_url_raw( $config['url_path'] );
		}
	}

	/**
	 * Add the default Kirki control types.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array $control_types The control types array.
	 * @return array
	 */
	public function default_control_types( $control_types = array() ) {

		$this->control_types = array(
			'checkbox'              => 'WP_Customize_Control',
			'kirki-background'      => 'Kirki_Control_Background',
			'kirki-code'            => 'Kirki_Control_Code',
			'kirki-color'           => 'Kirki_Control_Color',
			'kirki-color-palette'   => 'Kirki_Control_Color_Palette',
			'kirki-custom'          => 'Kirki_Control_Custom',
			'kirki-date'            => 'Kirki_Control_Date',
			'kirki-dashicons'       => 'Kirki_Control_Dashicons',
			'kirki-dimension'       => 'Kirki_Control_Dimension',
			'kirki-dimensions'      => 'Kirki_Control_Dimensions',
			'kirki-editor'          => 'Kirki_Control_Editor',
			'kirki-multicolor'      => 'Kirki_Control_Multicolor',
			'kirki-multicheck'      => 'Kirki_Control_MultiCheck',
			'kirki-number'          => 'Kirki_Control_Number',
			'kirki-palette'         => 'Kirki_Control_Palette',
			'kirki-preset'          => 'Kirki_Control_Preset',
			'kirki-radio'           => 'Kirki_Control_Radio',
			'kirki-radio-buttonset' => 'Kirki_Control_Radio_ButtonSet',
			'kirki-radio-image'     => 'Kirki_Control_Radio_Image',
			'repeater'              => 'Kirki_Control_Repeater',
			'kirki-select'          => 'Kirki_Control_Select',
			'kirki-slider'          => 'Kirki_Control_Slider',
			'kirki-sortable'        => 'Kirki_Control_Sortable',
			'kirki-spacing'         => 'Kirki_Control_Dimensions',
			'kirki-switch'          => 'Kirki_Control_Switch',
			'kirki-generic'         => 'Kirki_Control_Generic',
			'kirki-toggle'          => 'Kirki_Control_Toggle',
			'kirki-typography'      => 'Kirki_Control_Typography',
			'image'                 => 'WP_Customize_Image_Control',
			'cropped_image'         => 'WP_Customize_Cropped_Image_Control',
			'upload'                => 'WP_Customize_Upload_Control',
		);
		return array_merge( $control_types, $this->control_types );

	}

	/**
	 * Helper function that adds the fields, sections and panels to the customizer.
	 *
	 * @return void
	 */
	public function add_to_customizer() {
		$this->fields_from_filters();
		add_action( 'customize_register', array( $this, 'register_control_types' ) );
		add_action( 'customize_register', array( $this, 'add_panels' ), 97 );
		add_action( 'customize_register', array( $this, 'add_sections' ), 98 );
		add_action( 'customize_register', array( $this, 'add_fields' ), 99 );
		/* new Kirki_Modules_Loading(); */
	}

	/**
	 * Register control types
	 *
	 * @return  void
	 */
	public function register_control_types() {
		global $wp_customize;

		$section_types = apply_filters( 'kirki/section_types', array() );
		foreach ( $section_types as $section_type ) {
			$wp_customize->register_section_type( $section_type );
		}
		if ( empty( $this->control_types ) ) {
			$this->control_types = $this->default_control_types();
		}
		$do_not_register_control_types = apply_filters( 'kirki/control_types/exclude', array(
			'Kirki_Control_Repeater',
		) );
		foreach ( $this->control_types as $control_type ) {
			if ( 0 === strpos( $control_type, 'Kirki' ) && ! in_array( $control_type, $do_not_register_control_types ) ) {
				$wp_customize->register_control_type( $control_type );
			}
		}
	}

	/**
	 * Register our panels to the WordPress Customizer.
	 *
	 * @access public
	 */
	public function add_panels() {
		if ( ! empty( Kirki::$panels ) ) {
			foreach ( Kirki::$panels as $panel_args ) {
				new Kirki_Panel( $panel_args );
			}
		}
	}

	/**
	 * Register our sections to the WordPress Customizer.
	 *
	 * @var	object	The WordPress Customizer object
	 * @return  void
	 */
	public function add_sections() {
		if ( ! empty( Kirki::$sections ) ) {
			foreach ( Kirki::$sections as $section_args ) {
				new Kirki_Section( $section_args );
			}
		}
	}

	/**
	 * Create the settings and controls from the $fields array and register them.
	 *
	 * @var	object	The WordPress Customizer object
	 * @return  void
	 */
	public function add_fields() {

		global $wp_customize;
		foreach ( Kirki::$fields as $args ) {

			// Create the settings.
			new Kirki_Settings( $args );

			// Check if we're on the customizer.
			// If we are, then we will create the controls, add the scripts needed for the customizer
			// and any other tweaks that this field may require.
			if ( $wp_customize ) {

				// Create the control.
				new Kirki_Control( $args );

			}
		}
	}

	/**
	 * Build the variables.
	 *
	 * @return array 	('variable-name' => value)
	 */
	public static function get_variables() {

		$variables = array();

		// Loop through all fields.
		foreach ( Kirki::$fields as $field ) {

			// Check if we have variables for this field.
			if ( isset( $field['variables'] ) && $field['variables'] && ! empty( $field['variables'] ) ) {

				// Loop through the array of variables.
				foreach ( $field['variables'] as $field_variable ) {

					// Is the variable ['name'] defined? If yes, then we can proceed.
					if ( isset( $field_variable['name'] ) ) {

						// Sanitize the variable name.
						$variable_name = esc_attr( $field_variable['name'] );

						// Do we have a callback function defined? If not then set $variable_callback to false.
						$variable_callback = ( isset( $field_variable['callback'] ) && is_callable( $field_variable['callback'] ) ) ? $field_variable['callback'] : false;

						// If we have a variable_callback defined then get the value of the option
						// and run it through the callback function.
						// If no callback is defined (false) then just get the value.
						if ( $variable_callback ) {
							$variables[ $variable_name ] = call_user_func( $field_variable['callback'], Kirki::get_option( $field['settings'] ) );
						} else {
							$variables[ $variable_name ] = Kirki::get_option( $field['settings'] );
						}
					}
				}
			}
		}

		// Pass the variables through a filter ('kirki/variable') and return the array of variables.
		return apply_filters( 'kirki/variable', $variables );

	}

	/**
	 * Process fields added using the 'kirki/fields' and 'kirki/controls' filter.
	 * These filters are no longer used, this is simply for backwards-compatibility.
	 */
	public function fields_from_filters() {

		$fields = apply_filters( 'kirki/controls', array() );
		$fields = apply_filters( 'kirki/fields', $fields );

		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				Kirki::add_field( 'global', $field );
			}
		}

	}

	/**
	 * Handle saving of settings with "user_meta" storage type.
	 *
	 * @param string $value The value being saved.
	 * @param object $wp_customize_setting $WP_Customize_Setting The WP_Customize_Setting instance when saving is happening.
	 */
	public function update_user_meta( $value, $wp_customize_setting ) {
		update_user_meta( get_current_user_id(), $wp_customize_setting->id, $value );
	}
}
