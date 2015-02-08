<?php
/**
 * @file vehicle custom post type
 */

namespace Autostock\CarPostType;

use Autostock\CarPostType;

class Car_Post_Type {

	/**
	 * Admin options
	 * @var
	 */
	private $options;
	private $screen;


	/**
	 * Construct function for car post types.
	 */
	public function __construct() {

		$this->register_post_type();
		$this->register_taxonomies();
		add_filter( 'enter_title_here', array( $this, 'change_title_input' ) );
		add_action( 'edit_form_after_title', array( $this, 'reorder_meta_box' ) );
		add_filter( 'gettext', array( $this, 'change_text' ) );
		add_action( 'save_post', array( $this, 'vehicle_details_save_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'replace_features_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	}

	/**
	 * Function to register WordPress custom post type.
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => _x( 'Cars', 'post type general name', AUTO ),
			'singular_name'      => _x( 'Car', 'post type singular name', AUTO ),
			'menu_name'          => _x( 'Cars', 'admin menu', AUTO ),
			'name_admin_menu'    => _x( 'Car', 'add new on admin bar', AUTO ),
			'add_new'            => _x( 'Add Car', 'car', AUTO ),
			'add_new_item'       => __( 'Add New Car', AUTO ),
			'new_item'           => __( 'New Car', AUTO ),
			'edit_item'          => __( 'Edit Car', AUTO ),
			'view_item'          => __( 'View Car', AUTO ),
			'all_items'          => __( 'All Cars', AUTO ),
			'search_items'       => __( 'Search Cars', AUTO ),
			'parent_item_colon'  => __( 'Parent Car', AUTO ),
			'not_found'          => __( 'No cars found', AUTO ),
			'not_found_in_trash' => __( 'No cars found in trash', AUTO ),
		);

		$args = array(
			'labels'               => $labels,
			'description'          => 'Used car stock',
			'public'               => true,
			'exclude_from_search'  => false,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'show_in_nav_menus'    => true,
			'show_in_menu'         => true,
			'show_in_admin_bar'    => true,
			'menu_position'        => 2,
			'menu_icon'            => 'dashicons-backup',
			'hierarchical'         => false,
			'supports'             => array( 'title', 'author', 'excerpt', 'editor', 'thumbnail' ),
			'register_meta_box_cb' => array( $this, 'register_meta_box' ),
			'has_archive'          => true,
		);

		register_post_type( 'Cars', $args );
	}

	/**
	 * Function to create taxonomies for car post type
	 */
	public function register_taxonomies() {

		// Taxonomy for vehicle types
		$labels = array(
			'name'              => _x( 'Vehicle Makes and Models', 'taxonomy general name', AUTO ),
			'singular'          => _x( 'Make and Model', 'taxonomy singular name', AUTO ),
			'search_items'      => __( 'Search Makes/Models', AUTO ),
			'all_items'         => __( 'All Makes and Models', AUTO ),
			'parent_item'       => __( 'Parent Make and Model', AUTO ),
			'parent_item_colon' => __( 'Parent Make and Model', AUTO ),
			'edit_item'         => __( 'Edit Make and Model', AUTO ),
			'update_item'       => __( 'Update Make and Model', AUTO ),
			'add_new_item'      => __( 'Add New Make or Model', AUTO ),
			'new_item_name'     => __( 'New Make or Model', AUTO ),
			'menu_name'         => __( 'Makes and Models', AUTO ),
			'popular_items'     => __( 'Popular Makes and Models', AUTO ),
			'not_found'         => __( 'Make or Model not found', AUTO )
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug', 'vehicle_makes_models' ),
			'show_cloud'        => true,
		);
		register_taxonomy(
			'car_make_model',
			'cars',
			$args
		);

		// Taxonomy for car features
		$labels = array(
			'name'              => _x( 'Car Features', 'taxonomy general name', AUTO ),
			'singular'          => _x( 'Car Feature', 'taxonomy singular name', AUTO ),
			'search_items'      => __( 'Search Car Feature ', AUTO ),
			'all_items'         => __( 'All Car Features', AUTO ),
			'parent_item'       => __( 'Parent Car Feature', AUTO ),
			'parent_item_colon' => __( 'Parent Car Feature', AUTO ),
			'edit_item'         => __( 'Edit Car Feature', AUTO ),
			'update_item'       => __( 'Update Car Feature', AUTO ),
			'add_new_item'      => __( 'Add New Car Feature', AUTO ),
			'new_item_name'     => __( 'New Car Feature', AUTO ),
			'menu_name'         => __( 'Car Features', AUTO ),
			'popular_items'     => __( 'Popular Car Features', AUTO ),
			'not_found'         => __( 'Car feature not found', AUTO )
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => array( 'slug', 'car_features' ),
			'show_cloud'        => true,
		);
		register_taxonomy(
			'car_features',
			'cars',
			$args
		);

		// Car Types
		$labels = array(
			'name'              => _x( 'Car Types', 'taxonomy general name', AUTO ),
			'singular'          => _x( 'Car Type', 'taxonomy singular name', AUTO ),
			'search_items'      => __( 'Search Car Type ', AUTO ),
			'all_items'         => __( 'All Car Types', AUTO ),
			'parent_item'       => __( 'Parent Car Type', AUTO ),
			'parent_item_colon' => __( 'Parent Car Type', AUTO ),
			'edit_item'         => __( 'Edit Car Type', AUTO ),
			'update_item'       => __( 'Update Car Type', AUTO ),
			'add_new_item'      => __( 'Add New Car Type', AUTO ),
			'new_item_name'     => __( 'New Car Type', AUTO ),
			'menu_name'         => __( 'Car Types', AUTO ),
			'popular_items'     => __( 'Popular Car Types', AUTO ),
			'not_found'         => __( 'Car type not found', AUTO )
		);
		$args   = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => array( 'slug', 'car_types' ),
			'show_cloud'        => true,
		);
		register_taxonomy(
			'car_types',
			'cars',
			$args
		);
	}

	/**
	 * callback function for register_post_type to generate
	 * meta boxes for cars post type. Possible required for others.
	 */
	public function register_meta_box() {
		// Options set in admin page
		$this->options = get_option( 'car_details_options' );

		add_meta_box(
			'stock_number_meta_box',
			__( 'Vehicle Details', AUTO ),
			array( $this, 'render_vehicle_details_meta_box' ),
			'cars',
			'car_meta_box_position',
			'high'
		);

	}

	public function replace_features_meta_box() {
		// Remove the default meta box for the features categories
		remove_meta_box( 'car_featuresdiv', 'cars', 'side' );
		add_meta_box(
			'car_features_taxonomy',
			__( 'Car Features', AUTO ),
			array( $this, 'car_features_render_box_callback' ),
			'cars',
			'car_meta_box_position',
			'core'
		);

		// Remove the default meta box for car types
		remove_meta_box( 'tagsdiv-car_types', 'cars', 'side' );
	}

	/**
	 * Function to reorder car post types' meta boxes
	 */
	public function reorder_meta_box() {

		global $post, $wp_meta_boxes;

		do_meta_boxes( get_current_screen(), 'car_meta_box_position', $post );

		unset( $wp_meta_boxes['cars']['car_meta_box_position'] );
	}

	/**
	 * Function to change some of the default text in the custom post type admin edit screen
	 *
	 * @param $translation
	 *
	 * @return string|void
	 * @internal param null $original
	 *
	 */
	public function change_text( $translation ) {

		if ( 'Author' == $translation ) {
			return __( 'Sales Person' );
		}

		if ( 'Excerpt' == $translation ) {
			return __( 'Short Description', AUTO );
		} else {
			$pos = strpos( $translation, 'Excerpts are optional hand-crafted summaries of your' );
			if ( $pos !== false ) {
				return __( 'Short Descriptions are powerful SEO elements.', AUTO );
			}
		}

		return $translation;
	}

	/**
	 * Callback function to render meta box for vehicle details.
	 *
	 * @param $post
	 */
	public function render_vehicle_details_meta_box( $post ) {

		wp_nonce_field( 'vehicle_details_meta_box', 'vehicle_details_nonce' );


		$value = get_post_meta( $post->ID, '_vehicle_details', true );

		echo '<p>';
		echo '<label for="stock_sold"><strong>' . __( 'Sold', AUTO ) . '</strong></label><br>';
		$checked = isset( $value['stock_sold'] ) && $value['stock_sold'] == 1 ? 'checked' : '';
		echo '<input type="checkbox" name="stock_sold" id="stock_sold" value="1" ' . $checked . '>';
		echo '<i>' . __( 'Never delete stock as it is really bad for SEO, mark it as sold', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="featured_stock"><strong>' . __( 'Featured', AUTO ) . '</strong></label><br>';
		$checked = isset( $value['featured_stock'] ) && $value['featured_stock'] == 1 ? 'checked' : '';
		echo '<input type="checkbox" name="featured_stock" id="featured_stock" value="1" ' . $checked . '>';
		echo '<i>' . __( 'Display as featured item, useful for templates, galleries and sliders', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="stock_no"><strong>' . __( 'Stock No.', AUTO ) . '</strong></label><br>';
		$stock_no = isset( $value['stock_no'] ) ? esc_attr( $value['stock_no'] ) : '';
		echo '<input type="text" id="stock_no" name="stock_no" value="' . $stock_no . '">';
		echo '<br><i>' . __( 'Enter a unique stock number for this vehicle', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="sub_title"><strong>' . __( 'Sub Title' ) . '</strong></label><br>';
		$sub_title = isset( $value['sub_title'] ) ? esc_attr( $value['sub_title'] ) : '';
		echo '<input type="text" id="sub_title" name="sub_title" value="' . $sub_title . '">';
		echo '<br><i>' . __( 'You can optionally add a sub title here' ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="vin"><strong>' . __( 'VIN', AUTO ) . '</strong></label><br>';
		$vin = isset( $value['vin'] ) ? esc_attr( $value['vin'] ) : '';
		echo '<input type="text" id="vin" name="vin" value="' . $vin . '">';
		echo '<br><i>' . __( 'VIN (Vehicle Identification Number), Required by some countries', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="chassis"><strong>' . __( 'Chassis Number', AUTO ) . '</strong></label><br>';
		$chassis = isset( $value['chassis'] ) ? esc_attr( $value['chassis'] ) : '';
		echo '<input type="text" id="chassis" name="chassis" value="' . $chassis . '">';
		echo '<br><i>' . __( 'Chassis number for this vehicle', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="rego"><strong>' . __( 'Plate', AUTO ) . '</strong></label><br>';
		$rego = isset( $value['rego'] ) ? esc_attr( $value['rego'] ) : '';
		echo '<input type="text" name="rego" id="rego" value="' . esc_attr( $rego ) . '" >';
		echo '<br><i>' . __( 'Registration Number Plate', AUTO ) . '</i>';
		echo '</p>';

		// check to see if we have latest year
		if ( $this->options['latest_year'] ) {
			$latest_year = esc_attr( $this->options['latest_year'] );
		} else {
			$latest_year = ( new \DateTime() )->format( 'Y' );
		}
		if ( $this->options['earliest_year'] ) {
			$earliest_year = esc_attr( $this->options['earliest_year'] );
		} else {
			$earliest_year = $latest_year - 20;
		}

		echo '<p>';
		echo '<label for="year"><strong>' . __( 'Year', AUTO ) . '</strong></label><br>';
		echo '<select name="vehicle_year" id="vehicle_year">';
		for ( $latest_year; $latest_year >= $earliest_year; $latest_year -- ) {
			echo '<option value="' . $latest_year . '" ' . selected( $value['vehicle_year'], $latest_year ) . '>' . $latest_year . '</option>';
		}
		echo '</select>';
		echo '<br><i>' . __( 'Select vehicle year', AUTO ) . '</i>';
		echo '</p>';

		$odometer_type = isset( $this->options['odometer'][0] ) ? esc_attr( $this->options['odometer'][0] ) : 'kilometres';

		echo '<p>';
		echo '<label for="odometer"><strong>' . __( ucfirst( $odometer_type ), AUTO ) . '</strong></label><br>';
		$odometer = isset( $value['odometer'] ) ? esc_attr( $value['odometer'] ) : '';
		echo '<input type="text" name="odometer" id="odometer" value="' . $odometer . '" >';
		echo '<br><i>' . __( ' (use only digits)', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="car_types"><strong>' . __( 'Vehicle Type', AUTO ) . '</strong></label><br>';
		echo '<input type="hidden" name="tax_input[car_types][]" value="0">';
		$selected_array = wp_get_post_terms( $post->ID, 'car_types' );

		if ( empty( $selected_array ) ) {
			$selected = '';
		} else {
			// We know this can only be one so let's just drill down the array
			$selected = $selected_array[0]->term_id;
		}

		$args = array(
			'show_option_none' => 'Please select',
			'taxonomy'         => 'car_types',
			'hide_empty'       => 0,
			'name'             => 'car_types',
			'id'               => 'car_types',
			'selected'         => $selected
		);
		wp_dropdown_categories( $args );
		echo '</p>';

		echo '<p>';
		echo '<label for="engine"><strong>' . __( 'Engine', AUTO ) . '</strong></label><br>';
		$engine = isset( $value['engine'] ) ? esc_attr( $value['engine'] ) : '';
		echo '<input type="text" name="engine" id="engine" value="' . esc_attr( $engine ) . '" >';
		echo '<br><i>' . __( 'Engine', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="drive"><strong>' . __( 'Drive', AUTO ) . '</strong></label><br>';
		$drive = isset( $value['drive'] ) ? esc_attr( $value['drive'] ) : '';
		echo '<input type="text" name="drive" id="engine" value="' . esc_attr( $drive ) . '" >';
		echo '<br><i>' . __( 'Drive', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="transmission"><strong>' . __( 'Transmission', AUTO ) . '</strong></label><br>';
		$transmission = isset( $value['transmission'] ) ? esc_attr( $value['transmission'] ) : '';
		echo '<input type="text" name="transmission" id="transmission" value="' . esc_attr( $transmission ) . '" >';
		echo '<br><i>' . __( 'Transmission', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="fuel"><strong>' . __( 'Fuel', AUTO ) . '</strong></label><br>';
		$fuel = isset( $value['fuel'] ) ? esc_attr( $value['fuel'] ) : '';
		echo '<input type="text" name="fuel" id="fuel" value="' . esc_attr( $fuel ) . '" >';
		echo '<br><i>' . __( 'Fuel Type, ie petrol, diesel', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="seats"><strong>' . __( 'Seats', AUTO ) . '</strong></label><br>';
		$seats = isset( $value['seats'] ) ? esc_attr( $value['seats'] ) : '';
		echo '<input type="text" name="seats" id="seats" value="' . esc_attr( $seats ) . '" >';
		echo '<br><i>' . __( 'Number of seats (digital only)', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="condition"><strong>' . __( 'Condition', AUTO ) . '</strong></label><br>';
		$condition = isset( $value['condition'] ) ? esc_attr( $value['condition'] ) : '';
		echo '<input type="text" name="condition" id="condition" value="' . esc_attr( $condition ) . '" >';
		echo '<br><i>' . __( 'Overall condition', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="exterior"><strong>' . __( 'Exterior', AUTO ) . '</strong></label><br>';
		$exterior = isset( $value['exterior'] ) ? esc_attr( $value['exterior'] ) : '';
		echo '<input type="text" name="exterior" id="exterior" value="' . esc_attr( $exterior ) . '" >';
		echo '<br><i>' . __( 'Exterior', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="interior"><strong>' . __( 'Interior', AUTO ) . '</strong></label><br>';
		$exterior = isset( $value['interior'] ) ? esc_attr( $value['interior'] ) : '';
		echo '<input type="text" name="interior" id="interior" value="' . esc_attr( $exterior ) . '" >';
		echo '<br><i>' . __( 'Exterior', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="price"><strong>' . __( 'Price', AUTO ) . '</strong></label><br>';
		$price = isset( $value['price'] ) ? esc_attr( $value['price'] ) : '';
		echo '<input type="text" name="price" id="price" value="' . esc_attr( $price ) . '" >';
		echo '<br><i>' . __( 'Price (Digits only)', AUTO ) . '</i>';
		echo '</p>';

		echo '<p>';
		echo '<label for="sale"><strong>' . __( 'Sale Price', AUTO ) . '</strong></label><br>';
		$sale = isset( $value['sale'] ) ? esc_attr( $value['sale'] ) : '';
		echo '<input type="text" name="sale" id="sale" value="' . esc_attr( $sale ) . '" >';
		echo '<br><i>' . __( 'Sale Price (Digits only)', AUTO ) . '</i>';
		echo '</p>';
	}

	public function car_features_render_box_callback( $post ) {
		$taxonomy = 'car_features';
		$tax      = get_taxonomy( $taxonomy );
		$selected = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		?>

		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
			<input type="hidden" name="tax_input[<?php echo $taxonomy; ?>][]" value="0">
			<ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists="lists:<?php echo $taxonomy; ?> class="
			    categorychecklist form-no-clear
			">
			<?php
			wp_terms_checklist( $post->ID, array(
				'taxonomy'      => $taxonomy,
				'selected_cats' => $selected,
				'checked_ontop' => false,
			) );
			?>
			</ul>

		</div>
	<?php

	}

	/**
	 * WordPress hook add_action on save post.
	 *
	 * @param $post_id
	 */
	public function vehicle_details_save_meta_box( $post_id ) {


		// Check nonce is set
		if ( ! isset( $_POST['vehicle_details_nonce'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['vehicle_details_nonce'], 'vehicle_details_meta_box' ) ) {
			return;
		}

		// Check that this is not an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user has permission to edit or create a car type
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Should be safe to save the post meta.
		// TODO probably a good idea to create some options as set fields compulsory.

		// Sanitize data and put into an array.
		$data = array();

		$data['stock_no']  = sanitize_text_field( $_POST['stock_no'] );
		$data['sub_title'] = sanitize_text_field( $_POST['sub_title'] );
		$data['vin']       = sanitize_text_field( $_POST['vin'] );

		$data['chassis']        = sanitize_text_field( $_POST['chassis'] );
		$data['vehicle_year']   = sanitize_text_field( $_POST['vehicle_year'] );
		$data['odometer']       = preg_replace( '/\D/', '', $_POST['odometer'] );
		$data['stock_sold']     = isset( $_POST['stock_sold'] ) ? (int) $_POST['stock_sold'] : 0;
		$data['featured_stock'] = isset( $_POST['featured_stock'] ) ? (int) $_POST['featured_stock'] : 0;
		$data['rego']           = sanitize_text_field( ( $_POST['rego'] ) );
		$data['plate']          = sanitize_text_field( $_POST['plate'] );
		$data['engine']         = sanitize_email( $_POST['engine'] );
		$data['drive']          = sanitize_email( $_POST['drive'] );
		$data['fuel']           = sanitize_text_field( $_POST['fuel'] );
		$data['seats']          = preg_replace( '/\D/', '', $_POST['seats'] );
		$data['condition']      = sanitize_text_field( $_POST['condition'] );
		$data['exterior']       = sanitize_text_field( $_POST['exterior'] );
		$data['interior']       = sanitize_text_field( $_POST['interior'] );
		$data['price']          = preg_replace( '/\D/', '', $_POST['price'] );
		$data['sale']           = preg_replace( '/\D/', '', $_POST['sale'] );

		// if the data array is not empty then save the meta data array.
		if ( ! empty( $data ) ) {
			update_post_meta( $post_id, '_vehicle_details', $data );
		}

		if ( isset( $_POST['car_types'] ) ) {
			wp_set_post_terms( $post_id, array( (int) $_POST['car_types'] ), 'car_types', false );
		}

	}


	/**
	 * Function to change the title input field placeholder text
	 *
	 * @param $title
	 *
	 * @return string|void
	 */
	public function change_title_input( $title ) {
		$this->screen = get_current_screen();
		if ( 'cars' == $this->screen->post_type ) {
			$title = __( 'Enter vehicle title here', AUTO );
		}

		return $title;
	}

	public function enqueue_admin_scripts() {
		wp_register_style( 'autostock_admin_css', plugins_url( 'css/autostock_admin.css', dirname( __FILE__ ) ) );
		wp_enqueue_script( 'autostockjs', plugin_dir_url( dirname( __FILE__ ) ) . '/js/autostock.js' );
		wp_enqueue_style( 'autostock_admin_css' );
	}

}

new Car_Post_type();
