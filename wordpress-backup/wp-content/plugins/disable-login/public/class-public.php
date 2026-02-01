<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://endif.media
 * @since      1.0.0
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/public
 * @author     Ethan Allen <yourfriendethan@gmail.com>
 */
class Disable_Login_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Disable_Login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Disable_Login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugins_url() . '/' . $this->plugin_name . '/public/css/public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function login_disabled_message() {

	    $message = '<h1 class="disable-login-msg">' . __('Sit tight. We are working on your website', $this->plugin_name) . '<br><span>' . __("Unfortunately you won't be able to login until we are done.", $this->plugin_name) . '</span></h1>';
	    return $message;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function verify_user_is_allowed_to_login($username) {

		//get master username
	    $master = get_site_option('disable_login_settings');
	    
	    if($username != $master['allowed_user']){

	    	wp_logout();
	    	add_filter( 'login_redirect', create_function( '$error', "return wp_login_url();" ), 10, 3 );

	    }

	}

}