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
		remove_meta_box( 'car_featuresdiv', 'cars', 'side');
		add_meta_box(
			'car_features_taxonomy',
			__( 'Car Features', AUTO),
			array( $this, 'car_features_render_box_callback'),
			'cars',
			'car_meta_box_position',
			'core'
		);

		// Remove the default meta box for car types
		remove_meta_box( 'tagsdiv-car_types', 'cars', 'side');
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

		echo '<p>' . __( 'Enter a unique stock number for this vehicle', AUTO ) . '</p>';
		$stock_no = isset( $value['stock_no'] ) ? esc_attr( $value['stock_no'] ) : '';
		echo '<input type="text" id="stock_no" name="stock_no" value="' . $stock_no . '">';

		echo '<p>' . __( 'You can optionally add a sub title here' ) . '</p>';
		$sub_title = isset( $value['sub_title'] ) ? esc_attr( $value['sub_title'] ) : '';
		echo '<input type="text" id="sub_title" name="sub_title" value="' . $sub_title . '">';

		echo '<p>' . __( 'Enter the VIN for this vehicle', AUTO ) . '</p>';
		$vin = isset( $value['vin'] ) ? esc_attr( $value['vin'] ) : '';
		echo '<input type="text" id="vin" name="vin" value="' . $vin . '">';

		echo '<p>' . __( 'Enter the chassis number for this vehicle', AUTO ) . '</p>';
		$chassis = isset( $value['chassis'] ) ? esc_attr( $value['chassis'] ) : '';
		echo '<input type="text" id="chassis" name="chassis" value="' . $chassis . '">';

		// lets use the oop way

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

		echo '<p>' . __( 'Select vehicle year', AUTO ) . '</p>';
		echo '<select name="vehicle_year" id="vehicle_year">';
		/** @noinspection PhpExpressionResultUnusedInspection */
		for ( $latest_year; $latest_year >= $earliest_year; $latest_year -- ) {
			echo '<option value="' . $latest_year . '" ' . selected( $value['vehicle_year'], $latest_year ) . '>' . $latest_year . '</option>';
		}
		echo '</select>';

		$odometer_type = isset( $this->options['odometer'][0] ) ? esc_attr( $this->options['odometer'][0] ) : 'kilometres';

		echo '<p>' . __( ucfirst( $odometer_type ), AUTO ) . __( ' (use only digits)', AUTO ) . '</p>';
		$odometer = isset( $value['odometer'] ) ? esc_attr( $value['odometer'] ) : '';
		echo '<input type="text" name="odometer" id="odometer" value="' . $odometer . '" >';

		echo '<p>' . __( 'Vehicle Type', AUTO ) . '</p>';
		echo '<input type="hidden" name="tax_input[car_types][]" value="0">';
		$selected_array = wp_get_post_terms( $post->ID, 'car_types');

		if ( empty( $selected_array ) ) {
			$selected = '';
		} else {
			// We know this can only be one so let's just drill down the array
			$selected = $selected_array[0]->term_id;
		}

			$args = array(
			'show_option_none' => 'Please select',
			'taxonomy' => 'car_types',
			'hide_empty' => 0,
			'name' => 'car_types',
			'id' => 'car_types'
		);
		wp_dropdown_categories( $args );

	}

	public function car_features_render_box_callback( $post ) {
		$taxonomy = 'car_features';
		$tax = get_taxonomy( $taxonomy);
		$selected = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		?>

		<div id="taxonomy-<?php echo $taxonomy;?>" class="categorydiv">
			<input type="hidden" name="tax_input[<?php echo $taxonomy;?>][]" value="0">
			<ul id="<?php echo $taxonomy;?>checklist" data-wp-lists="lists:<?php echo $taxonomy;?> class="categorychecklist form-no-clear">
				<?php
				wp_terms_checklist( $post->ID, array(
					'taxonomy' => $taxonomy,
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

		$data['chassis']      = sanitize_text_field( $_POST['chassis'] );
		$data['vehicle_year'] = sanitize_text_field( $_POST['vehicle_year'] );
		$data['odometer']     = preg_replace( '/\D/', '', $_POST['odometer'] );

		// if the data array is not empty then save the meta data array.
		if ( ! empty( $data ) ) {
			update_post_meta( $post_id, '_vehicle_details', $data );
		}

		if ( isset( $_POST['car_types'] ) ) {
			wp_set_post_terms( $post_id, array( (int)$_POST['car_types'] ), 'car_types', false );
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
		wp_register_style( 'autostock_admin_css', plugins_url('css/autostock_admin.css', dirname( __FILE__ ) ) );
		wp_enqueue_script('autostockjs', plugin_dir_url( dirname(__FILE__) ). '/js/autostock.js' );
		wp_enqueue_style( 'autostock_admin_css');
	}

}

new Car_Post_type();
