<?php
/**
 * @file vehicle custom post type
 */

namespace Autostock\CarPostType;

use Autostock\CarPostType;

class Car_Post_Type {

	private $screen;

	/**
	 * Construct function for car post types.
	 */
	public function __construct() {
		$this->register_post_type();
		add_filter( 'enter_title_here', array( $this, 'change_title_input' ) );
		add_action( 'edit_form_after_title', array( $this, 'reorder_meta_box' ) );
		add_action( 'admin_head', array( $this, 'remove_media_button' ) );
		add_filter( 'gettext', array( $this, 'change_text' ) );
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
			'supports'             => array( 'title', 'author', 'excerpt', 'editor' ),
			'register_meta_box_cb' => array( $this, 'register_meta_box' ),
			'has_archive'          => true,
		);

		register_post_type( 'Cars', $args );
	}

	/**
	 * callback function for register_post_type to generate
	 * meta boxes for cars post type.
	 *
	 * @param $post
	 */
	public function register_meta_box() {

		add_meta_box(
			'stock_number_meta_box',
			__( 'Vehicle Details', AUTO ),
			array( $this, 'render_vehicle_details_meta_box'),
			'cars',
			'car_meta_box_position',
			'high'
		);

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
	 * @param null $original
	 *
	 * @return string|void
	 */
	public function change_text( $translation, $original = null ) {

		if ( 'Author' == $translation) {
			return __( 'Sales Person' );
		}

		if ( 'Excerpt' == $translation ) {
			return __('Short Description', AUTO );
		}else{
			$pos = strpos($translation, 'Excerpts are optional hand-crafted summaries of your');
			if ($pos !== false) {
				return  __( 'Short Descriptions are powerful SEO elements.', AUTO );
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

		echo '<p>' . __('Enter a unique stock number for this vehicle', AUTO) . '</p>';
		echo '<input type="text" id="stock_no" name="stock_number">';
		echo '<p>' . __('Enter the VIN for this vehicle', AUTO) . '</p>';
		echo '<input type="text" id="vin" name="vin">';
		echo '<p>' . __('Enter the chassis number for this vehicle', AUTO) . '</p>';
		echo '<input type="text" id="chassis" name="chassis">';


	}

	public function register_taxonomies() {

	}

	/**
	 * Function to change the title input field placeholder text
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

	/**
	 * Function to remove add media button on the editor
	 */
	public function remove_media_button() {
		global $post;

		if ( isset($post ) && 'cars' == $post->post_type ) {
			remove_action( 'media_buttons', 'media_buttons' );
		}
	}

}

new Car_Post_Type();
