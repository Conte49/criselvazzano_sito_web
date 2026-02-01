<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://endif.media
 * @since      1.0.0
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Disable_Login
 * @subpackage Disable_Login/includes
 * @author     Ethan Allen <yourfriendethan@gmail.com>
 */
class Disable_Login {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'disable-login';
		$this->version = '1.1.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Disable_Login_Loader. Orchestrates the hooks of the plugin.
	 * - Disable_Login_i18n. Defines internationalization functionality.
	 * - Disable_Login_Admin. Defines all hooks for the admin area.
	 * - Disable_Login_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';

		/**
		 * The class responsible for defining and saving plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/class-plugin-options.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Disable_Login_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Disable_Login_i18n();

		add_action( 'plugins_loaded', array($plugin_i18n, 'load_plugin_textdomain') );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Disable_Login_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts') );


			/**
			 * Create menu icon and link.
			 */
			if (is_multisite()){				
				add_action( 'network_admin_menu', array($plugin_admin, 'create_network_menu') );
			} else {
				add_action( 'admin_menu', array($plugin_admin, 'create_menu') );
			}
			
			/**
			 * Upgrade available
			 */
			add_action( 'admin_notices', array($plugin_admin, 'display_premium_version_available_notice') );

			/**
			 * Nag transient.
			 */
			add_action( 'wp_ajax_set_disable_login_nag_transient', array($plugin_admin, 'set_premium_upgrade_notice_transient') );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Disable_Login_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'login_enqueue_scripts', array($plugin_public, 'enqueue_styles') );

			//get plugin options
			if (is_multisite()){
				$disable_login_settings = get_site_option('disable_login_settings');
			} else {
				$disable_login_settings = get_option('disable_login_settings');				
			}

		    if(!empty($disable_login_settings['enable_plugin']) && $disable_login_settings['enable_plugin'] == 1 ){

			    /**
				 * Check user capability
				 */
				add_action('wp_login', array($plugin_public, 'verify_user_is_allowed_to_login'), 3);

				/**
				 * Print lockout message
				 */
				add_filter('login_message', array($plugin_public, 'login_disabled_message'), 1);

			}

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
