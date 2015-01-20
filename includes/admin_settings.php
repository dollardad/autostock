<?php

namespace Autostock\AdminSettings;

use Autostock\AdminSettings;

class Admin_Settings {

	/**
	 * options Holds the values to be used in the fields callbacks
	 * @var
	 */
	private $options;
	private $car_features_options;


	/**
	 * Standard construct function
	 */
	public function __construct() {
		// set options array
		$this->options = get_option( 'autostock_car_details_options' );
		$this->car_features_options = get_option( 'autostock_car_features_options' );
		// add the admin menu page
		add_action( 'admin_menu', array( $this, 'autostock_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_car_details_general_settings_section' ) );
		//add_action( 'admin_init', array( $this, 'add_car_features_settings_section' ) );
		add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );
	}

	/**
	 * Function to generate admin menu pages
	 */
	public function autostock_admin_menu() {
		// Main admin page
		add_menu_page(
			'AutoStock Admin Settings',
			'Autostock',
			'manage_options',
			'autostock_admin_settings',
			array( $this, 'main_admin_page_callback')
		);

		// Sub menu page for car features

		add_submenu_page(
			'autostock_admin_settings',
			__( 'Car Features Settings', AUTO ),
			__( 'Car Features Settings', AUTO ),
			'manage_options',
			'car_features_setting',
			array( $this, 'car_features_default_settings_callback' )
		);
	}

	/**
	 * Callback for the main admin page
	 */
	public function main_admin_page_callback() {

		// Create header in settings page
		$display = '<div class="wrap">';
			$display .= '<h2>' . __( 'Autostock Settings', AUTO );
		$display .= '</div>'; // end wrap
		echo $display;

		// lets add the form
		echo '<form method="post" action="options.php">';
			settings_fields( 'autostock_car_details_group' );
			do_settings_sections( 'autostock_admin_settings' );
			submit_button();
		echo '</form>';
	}

	/**
	 * Register the different sections in the admin pages
	 */
	public function add_car_details_general_settings_section() {

		// Main Admin section
		register_setting(
			'autostock_car_details_group',
			'autostock_car_details_options',
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

		// Car features section
		register_setting(
			'autostock_car_features_group',
			'autostock_car_features_options',
			array( $this, 'sanitize_car_features_fields' ),
			$_POST
		);
		add_settings_section(
			'car_features_settings_section', // section id
			__( 'Settings for car features', AUTO ),
			array( $this, 'car_features_options_callback'),
			'car_features_setting'
		);
		add_settings_field(
			'add_default_features',
			'Add Default Features',
			array( $this, 'add_default_car_features_callback' ),
			'car_features_setting',
			'car_features_settings_section'
		);

	}

	/**
	 * Main admin page call back section
	 */
	public function car_details_general_options_callback() {
		echo '<p>' . __( 'Use this section for setting options when adding or editing a vehicle item', AUTO ) . '</p>';

	}


	/**
	 * Callback function for the years fields
	 */
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

	/**
	 * Callback function for the odometer field
	 */
	public function odometer_display_option_callback() {
		?>
		<p>

			<label title="mileage">
				<?php $odometer = (isset($this->options['odometer'][0]) && $this->options['odometer'][0] == 'mileage' ) ? 'mileage' : '';?>
				<input type="radio" name="odometer[]" value="mileage" <?php checked( $odometer, 'mileage' ); ?> >
				<span><?php echo __( 'Mileage', AUTO );?></span>
			</label>
			<br>
			<label title="kilometres">
				<?php $odometer = (isset($this->options['odometer'][0]) && $this->options['odometer'][0] == 'kilometres' ) ? 'kilometres' : '';?>
				<input type="radio" name="odometer[]" value="kilometres" <?php checked( $odometer, 'kilometres'); ?> >
				<span><?php echo __( 'Kilometres', AUTO );?></span>
			</label>
		</p>
		<?php
	}


	/**
	 * Callback function to sanitize the vehicle details fields
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize_car_details_fields( $input ) {

		if ( empty( $input) ) {
			$input = array();
		}

		$error = false;
		// Earliest year input field
		if ( isset( $_POST['earliest_year']) && null !=  $_POST['earliest_year'] )  {
			if ( ! is_numeric( $_POST['earliest_year'] ) ) {
				$input['earliest_year'] = '';
				add_settings_error( 'vehicle_details', 'earliest_year', __( 'Earliest year must be a 4 digit number', AUTO ), 'error');
				$error = true;
			} elseif ( is_numeric( $_POST['earliest_year'] ) && ! preg_match( '/\d{4}/', $_POST['earliest_year'] ) ) {
				$input['earliest_year'] = '';
				add_settings_error( 'vehicle_details', 'car_features_group', __( 'Earliest year must be a 4 digit number, ie 1997', AUTO ), 'error');
				$error = true;
			} else {
				$input['earliest_year'] = sanitize_text_field( $_POST['earliest_year'] );
			}
		}

		// Latest year input field
		if ( isset( $_POST['latest_year']) && null !=  $_POST['latest_year'] )  {
			if ( ! is_numeric( $_POST['latest_year'] ) ) {
				$input['latest_year'] = '';
				add_settings_error( 'vehicle_details', 'latest_year', __( 'Latest year must be a 4 digit number, ie 2007', AUTO ), 'error');
				$error = true;
			} elseif ( is_numeric( $_POST['latest_year'] ) && ! preg_match( '/\d{4}/', $_POST['latest_year'] ) ) {
				$input['latest_year'] = '';
				add_settings_error( 'vehicle_details', 'latest_year', __( 'Latest year must be a 4 digit number, ie 2007', AUTO ), 'error');
				$error = true;
			} else {
				$input['latest_year'] = sanitize_text_field( $_POST['latest_year'] );
			}
		}

		if ( isset( $_POST['odometer'] ) ) {
			$input['odometer'] = $_POST['odometer'];
		}
		if ( ! $error ) {
			add_settings_error( 'vehicle_details', 'success', __( 'Vehicle details have been updated', AUTO ), 'updated' );
		}
		return $input;
	}

	/**
	 * Callback function to display the features section
	 */
	public function car_features_default_settings_callback() {

		// Create header in section
		$display = '<div class="wrap">';
			$display .= '<h2>' . __( 'Car Feature Settings', AUTO ) . '</h2>';
		$display .= '</div>';
		echo $display;

		// lets add the form
		echo '<form method="post" action="options.php">';
		settings_fields( 'autostock_car_features_group' );
		do_settings_sections( 'car_features_setting' );
		submit_button();
		echo '</form>';
	}

	/**
	 * Callback function to display the features section
	 */
	public function car_features_options_callback() {
		echo '<p>' . __( 'You can install the default car features rather than manually entering them in the categories section', AUTO ) .'.</p>';
		echo '<p>' . __( 'Features can be edited or deleted at anytime through the taxonomy menu', AUTO) .'.</p>';
	}

	/**
	 * Callback function for the checkbox to add default car features
	 */
	public function add_default_car_features_callback() {

		$checked = isset( $this->car_features_options['add_default_features'] );
		echo '<input type="checkbox" name="add_default_features" value="1"' . checked( $checked, 1, false ) .'>';
		echo '<label>' . __( 'Select to install default vehicle features', AUTO) . '</label>';


	}

	/**
	 * Callback to the checkbox that will add features
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize_car_features_fields( $input ) {
		$input = array();

		if ( isset( $_POST['add_default_features'] ) && 1 == $_POST['add_default_features'] ) {

			$input['add_default_features'] = 1;

			if ( isset($this->car_features_options['add_default_features']) ) {
				// do nothing as it has already been loaded
			} else {
				// We now need to load the file with the default values
				$features = $this->array_default_features();
				foreach ( $features as $feature ) {

					wp_insert_term( $feature[0], $feature[1], $feature[2] );

				}
			}
			add_settings_error( 'vehicle_details', 'add_default_features', __( 'Features list has been updated to include defaults.', AUTO ), 'updated');

		}

		return $input;
	}


	/**
	 * Callback to call admin notices
	 */
	public function show_admin_notices() {
		settings_errors('vehicle_details');
	}

	/**
	 * Private function to hold array of car features
	 *
	 * @return array
	 */
	private function array_default_features() {

		$features = array(
			array( '2 Door', 'car_features', array( 'slug' => '2_door' ) ),
			array( '3 Door', 'car_features', array( 'slug' => '3_door' ) ),
			array( '4 Door', 'car_features', array( 'slug' => '4_door' ) ),
			array( '4WD/4x4', 'car_features', array( 'slug' => '4wd' ) ),
			array( '5 Door', 'car_features', array( 'slug' => '5_door' ) ),
			array( 'AA Approved', 'car_features', array( 'slug' => 'aa_approved' ) ),
			array( 'ABS Brakes', 'car_features', array( 'slug' => 'abs_brakes' ) ),
			array( 'Air Bag(s)', 'car_features', array( 'slug' => 'air_bags' ) ),
			array( 'Air Conditioning', 'car_features', array( 'slug' => 'air_conditioning' ) ),
			array( 'Alarm', 'car_features', array( 'slug' => 'alarm' ) ),
			array( 'All Electric', 'car_features', array( 'slug' => 'all_electric' ) ),
			array( 'Alloys', 'car_features', array( 'slug' => 'alloys' ) ),
			array( 'Alloys', 'car_features', array( 'slug' => 'alloys' ) ),
			array( 'Approved Used', 'car_features', array( 'slug' => 'approved_used' ) ),
			array( 'Bluetooth', 'car_features', array( 'slug' => 'bluetooth' ) ),
			array( 'Body Kit', 'car_features', array( 'slug' => 'body_kit' ) ),
			array( 'Bull Bars', 'car_features', array( 'slug' => 'bull_bars' ) ),
			array( 'Canopy', 'car_features', array( 'slug' => 'canopy' ) ),
			array( 'Car Stereo', 'car_features', array( 'slug' => 'car_stereo' ) ),
			array( 'Cassette', 'car_features', array( 'slug' => 'cassette' ) ),
			array( 'CD(s)', 'car_features', array( 'slug' => 'cds' ) ),
			array( 'Central Locking', 'car_features', array( 'slug' => 'central_locking' ) ),
			array( 'Climate Control', 'car_features', array( 'slug' => 'climate_control' ) ),
			array( 'Cruise Control', 'car_features', array( 'slug' => 'cruise_control' ) ),
			array( 'Demo', 'car_features', array( 'slug' => 'demo' ) ),
			array( 'Digital Dash', 'car_features', array( 'slug' => 'digital_dash' ) ),
			array( 'Disability Enabled', 'car_features', array( 'slug' => 'disability_enabled' ) ),
			array( 'Drop Sides', 'car_features', array( 'slug' => 'drop_sides' ) ),
			array( 'DVD', 'car_features', array( 'slug' => 'dvd' ) ),
			array( 'EFI', 'car_features', array( 'slug' => 'efi' ) ),
			array( 'Electric Aerial', 'car_features', array( 'slug' => 'electrical_aerial' ) ),
			array( 'Electric Mirrors', 'car_features', array( 'slug' => 'electrical_mirrors' ) ),
			array( 'Electric Seats', 'car_features', array( 'slug' => 'electrical_seats' ) ),
			array( 'Electric Windows', 'car_features', array( 'slug' => 'electrical_windows' ) ),
			array( 'Exhaust Brakes', 'car_features', array( 'slug' => 'exhaust_brakes' ) ),
			array( 'Fog Lights', 'car_features', array( 'slug' => 'fog_lights' ) ),
			array( 'Freezer', 'car_features', array( 'slug' => 'freezer' ) ),
			array( 'Fridge', 'car_features', array( 'slug' => 'fridge' ) ),
			array( 'GPS/SatNav', 'car_features', array( 'slug' => 'gps_satnav' ) ),
			array( 'Immobiliser', 'car_features', array( 'slug' => 'immobiliser' ) ),
			array( 'Imported', 'car_features', array( 'slug' => 'imported' ) ),
			array( 'Intercooler', 'car_features', array( 'slug' => 'intercooler' ) ),
			array( 'Leather Seats', 'car_features', array( 'slug' => 'leather_seats' ) ),
			array( 'LPG', 'car_features', array( 'slug' => 'lpg' ) ),
			array( 'MP3 Input', 'car_features', array( 'slug' => 'MP3 Input' ) ),
			array( 'New', 'car_features', array( 'slug' => 'new' ) ),
			array( 'Nudge Bar', 'car_features', array( 'slug' => 'nudge_bar' ) ),
			array( 'Overdrive', 'car_features', array( 'slug' => 'Overdrive' ) ),
			array( 'Parking Sensors', 'car_features', array( 'slug' => 'parking_sensors' ) ),
			array( 'Power Steering', 'car_features', array( 'slug' => 'power_steering' ) ),
			array( 'Power Take Off', 'car_features', array( 'slug' => 'power_take_off' ) ),
			array( 'Radio', 'car_features', array( 'slug' => 'radio' ) ),
			array( 'Remote Locking', 'car_features', array( 'slug' => 'remote_locking' ) ),
			array( 'Reversing Camera', 'car_features', array( 'slug' => 'Reversing Camera' ) ),
			array( 'Roof Racks', 'car_features', array( 'slug' => 'Roof Racks' ) ),
			array( 'Roof Rails', 'car_features', array( 'slug' => 'Roof Rails' ) ),
			array( 'Running Boards', 'car_features', array( 'slug' => 'Running Boards' ) ),
			array( 'Special', 'car_features', array( 'slug' => 'special' ) ),
			array( 'Spoiler', 'car_features', array( 'slug' => 'spoiler' ) ),
			array( 'Spot Lights', 'car_features', array( 'slug' => 'spot_lights' ) ),
			array( 'Sunroof', 'car_features', array( 'slug' => 'sunroof' ) ),
			array( 'Tail Lift', 'car_features', array( 'slug' => 'tail_lift' ) ),
			array( 'Tailgate', 'car_features', array( 'slug' => 'Tailgate' ) ),
			array( 'Tonneau Cover', 'car_features', array( 'slug' => 'tonneau_cover' ) ),
			array( 'Towbar', 'car_features', array( 'slug' => 'towbar' ) ),
			array( 'Traction Control', 'car_features', array( 'slug' => 'traction_control' ) ),
			array( 'Tuff Deck', 'car_features', array( 'slug' => 'tuff_deck' ) ),
			array( 'Turbo', 'car_features', array( 'slug' => 'turbo' ) ),
			array( 'Turbo Timer', 'car_features', array( 'slug' => 'turbo_timer' ) ),
			array( 'Used', 'car_features', array( 'slug' => 'Used' ) ),

		);
		return $features;
	}

}
new Admin_Settings();