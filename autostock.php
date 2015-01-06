<?php
	/**
	 * Plugin Name: Autostock
	 * Plugin URI: http://kevinphillips.co.nz
	 * Description: Car dealer stock management plugin for WordPress.
	 * Version: 1.0.0
	 * Author: Kevin Phillips
	 * Author URI: http://kevinphillips.co.nz
	 * Text Domain: autostock
	 * Domain Path: locale
	 * license: GPL2
	 */

/*  Copyright 2015  Kevin Phillips  (email : kevin@kevinphillips.co.nz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
namespace Autostock;
use Autostock;

// Plugin definitions.
define( 'AUTO', 'autostock' );
define( 'PHP_MIN_VERSION', '5.3.6' );

/**
 * Before we allow this plugin to be activated we must test the php version against the minimum requirements
 */
function autostock_activation() {

    if (version_compare(  PHP_MIN_VERSION, PHP_VERSION ) >= 0) {
        wp_die('Sorry your php version is ' . PHP_VERSION . ' and you need a minimum of ' . PHP_MIN_VERSION );
    }

}
register_activation_hook(__FILE__, __NAMESPACE__ . '\autostock_activation' );

// include files
function load_includes() {
    include 'includes/Car_Post_Type.php';
}
add_action( 'init', __NAMESPACE__ .'\load_includes' );

