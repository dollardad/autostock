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
			array( $this, 'sanitize_car_details_fields')
		);

		add_settings_section(
			'car_general_settings_section', // section id
			__( 'Settings for car details', AUTO ),
			array( $this, 'car_details_general_options_callback'),
			'autostock_admin_settings' // admin page slug
		);

		add_settings_field(
			'start_year',
			'Start Year',
			array( $this, 'start_year_callback'),
			'autostock_admin_settings', // admin page slug
			'car_general_settings_section' // section id
		);

		add_settings_field(
			'end_year',
			'End Year',
			array( $this, 'end_year_callback'),
			'autostock_admin_settings', // admin page slug
			'car_general_settings_section' // section id
		);

	}

	public function car_details_general_options_callback() {


		echo 'so we got to here so far';
	}


	public function start_year_callback() {
		echo 'start year stuff will go here';
	}

	public function end_year_callback() {
		echo 'end year stuff will go here';
	}

	public function sanitize_car_details_fields( /** @noinspection PhpUnusedParameterInspection */
		$input ) {

		$new_input = array();

		return $new_input;
	}




}
new Admin_Settings();