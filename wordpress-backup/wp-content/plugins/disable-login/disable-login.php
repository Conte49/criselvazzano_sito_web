<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://endif.media
 * @since             1.0.0
 * @package           Disable_Login
 *
 * @wordpress-plugin
 * Plugin Name:       Disable Login
 * Plugin URI:        http://endif.media
 * Description:       Disable login by certain users. Useful when you are moving a client site and don't want anyone to make any edits to the WordPress database. Now with multisite support!
 * Version:           1.1.1
 * Author:            Ethan Allen
 * Author URI:        http://endif.media
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disable-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-disable-login-activator.php
 */
function activate_disable_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Disable_Login_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_disable_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_disable_login() {

	$plugin = new Disable_Login();

}
run_disable_login();