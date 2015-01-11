<?php

namespace Autostock\AdminSettings;

use Autostock\AdminSettings;

class Admin_Settings {

	/**
	 * options Holds the values to be used in the fields callbacks
	 * @var
	 */
	private $options;

	public function __construct() {
		// add the admin menu page
		add_action( 'admin_menu', array( $this, 'autostock_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_car_details_general_settings_section' ) );
		add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );
	}

	public function autostock_admin_menu() {
		// Main admin page
		add_menu_page(
			'AutoStock Admin Settings',
			'Autostock',
			'manage_options',
			'autostock_admin_settings',
			array( $this, 'main_admin_page_callback')
		);
	}

	public function main_admin_page_callback() {

		// set options array
		$this->options = get_option( 'car_details_options' );

		// Create header in settings page
		$display = '<div class="wrap">';
			$display .= '<h2>' . __( 'Autostock Settings', AUTO );
		$display .= '</div>'; // end wrap
		echo $display;

		// lets add the form
		echo '<form method="post" action="options.php">';
			settings_fields( 'car_details_group' );
			do_settings_sections( 'autostock_admin_settings' );
			submit_button();
		echo '</form>';
	}

	public function add_car_details_general_settings_section() {

		register_setting(
			'car_details_group',
			'car_details_options',
			array( $this, 'sanitize_car_details_fields'),
			$_POST
		);

		add_settings_section(
			'car_general_settings_section', // section id
			__( 'Settings for car details', AUTO ),
			array( $this, 'car_details_general_options_callback'),
			'autostock_admin_settings' // admin page slug
		);


		add_settings_field(
			'vehicle_year_options',
			'Vehicle Year Options',
			array( $this, 'vehicle_year_options_callback'),
			'autostock_admin_settings', // admin page slug
			'car_general_settings_section' // section id
		);

		add_settings_field(
			'odometer_display_option',
			'Odometer Display Option',
			array( $this, 'odometer_display_option_callback' ),
			'autostock_admin_settings', // admin page slug
			'car_general_settings_section'
		);

	}

	public function car_details_general_options_callback() {
		echo '<p>' . __( 'Use this section for setting options when adding or editing a vehicle item', AUTO ) . '</p>';
		echo '<pre>';
		print_r($this->options);
		echo '</pre>';
	}


	public function vehicle_year_options_callback() {
		?>
		<p>
			<label for="earliest_year"><?php echo __( 'Earliest vehicle year you stock', AUTO );?></label>
			<?php $earliest_year = isset( $this->options['earliest_year'] ) ? esc_attr( $this->options['earliest_year']) : '';?>
			<input type="text" name="earliest_year" id="earliest_year" value="<?php echo $earliest_year; ?>">
			<br><span class="description">(<?php echo __('Leave blank for default, which is 20 years', AUTO ); ?>)</span>
		</p>
		<p>
			<label for="latest_year"><?php echo __( 'Latest vehicle year you stock', AUTO );?></label>
			<?php $latest_year = isset( $this->options['latest_year'] ) ? esc_attr( $this->options['latest_year']) : '';?>
			<input type="text" name="latest_year" id="latest_year" value="<?php echo $latest_year; ?>">
			<br><span class="description">(<?php echo __('Leave blank for default, which is this year', AUTO ); ?>)</span>
		</p>

		<?php
	}

	public function odometer_display_option_callback() {
		?>
		<p>

			<label title="mileage">
				<input type="radio" name="odometer[]" value="mileage" <?php checked( $this->options['odometer'][0], 'mileage' ); ?> >
				<span><?php echo __( 'Mileage', AUTO );?></span>
			</label>
			<br>
			<label title="kilometres">
				<input type="radio" name="odometer[]" value="kilometres" <?php checked( $this->options['odometer'][0], 'kilometres'); ?> >
				<span><?php echo __( 'Kilometres', AUTO );?></span>
			</label>
		</p>
		<?php
	}


	public function sanitize_car_details_fields( $input ) {



		if ( empty( $input) ) {
			$input = $_POST;
		}
		// Earliest year input field
		if ( isset( $input['earliest_year']) && null !=  $input['earliest_year'] )  {
			if ( ! is_numeric( $input['earliest_year'] ) ) {
				$input['earliest_year'] = '';
				add_settings_error( 'vehicle_details', 'earliest_year', __( 'Earliest year must be a 4 digit number', AUTO ), 'error');
			}
			if ( is_numeric( $input['earliest_year'] ) && ! preg_match( '/\d{4}/', $input['earliest_year'] ) ) {
				$input['earliest_year'] = '';
				add_settings_error( 'vehicle_details', 'earliest_year', __( 'Earliest year must be a 4 digit number, ie 1997', AUTO ), 'error');
			}
		}

		// Latest year input field
		if ( isset( $input['latest_year']) && null !=  $input['latest_year'] )  {
			if ( ! is_numeric( $input['latest_year'] ) ) {
				$input['latest_year'] = '';
				add_settings_error( 'vehicle_details', 'latest_year', __( 'Latest year must be a 4 digit number, ie 2007', AUTO ), 'error');
			}
			if ( is_numeric( $input['latest_year'] ) && ! preg_match( '/\d{4}/', $input['latest_year'] ) ) {
				$input['latest_year'] = '';
				add_settings_error( 'vehicle_details', 'latest_year', __( 'Latest year must be a 4 digit number, ie 2007', AUTO ), 'error');
			}
		}



		return $input;
	}

	public function show_admin_notices() {
		settings_errors('vehicle_details');
	}




}
new Admin_Settings();