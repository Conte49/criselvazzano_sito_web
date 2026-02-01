<?php

/**
 * Fired during plugin activation
 *
 * @link       http://endif.media
 * @since      1.0.0
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Disable_Login
 * @subpackage Disable_Login/includes
 * @author     Ethan Allen <yourfriendethan@gmail.com>
 */
class Disable_Login_Activator {

	/**
	 * Setup initial plugin settings.
	 *	 
	 * @since    1.0.0
	 */
	public static function activate() {
		
		if(get_option('disable_login_settings') == ''){

			$disable_login_settings = array(
				'allowed_user' => '', //user allowed access
				'enable_plugin' => '',
				'login_message' => ''
			);
			update_option('disable_login_settings', $disable_login_settings);

		} 

	}

}
