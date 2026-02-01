<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://endif.media
 * @since      1.0.0
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Disable_Login
 * @subpackage Disable_Login/admin
 * @author     Ethan Allen <yourfriendethan@gmail.com>
 */
class Disable_Login_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/disable-login-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register standard menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function create_menu(){
		$locked = get_option('disable_login_settings');
		if($locked['enable_plugin'] == 0){
			add_menu_page( 'Plugin Settings', 'Disable Login', 'manage_options', 'disable_login_settings', array( $this, 'options_page' ), 'dashicons-unlock' );
		} else {
			add_menu_page( 'Plugin Settings', 'Disable Login', 'manage_options', 'disable_login_settings', array( $this, 'options_page' ), 'dashicons-lock' );
		}
	}

	/**
	 * Register the network menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function create_network_menu(){
		$locked = get_site_option('disable_login_settings');
		if($locked['enable_plugin'] == 0){
			add_menu_page( 'Plugin Settings', 'Disable Login', 'manage_network_plugins', 'disable_login_settings', array( $this, 'options_page' ), 'dashicons-unlock' );
		} else{
			add_menu_page( 'Plugin Settings', 'Disable Login', 'manage_network_plugins', 'disable_login_settings', array( $this, 'options_page' ), 'dashicons-lock' );
		}
	}

	/**
	 * Function that displays the options form.
	 *
	 * @since    1.1.1
	 */
	public function options_page() {

		$options = $this->option_fields();
		$other = new Disable_Login_Plugin_Options('Disable Login', 'disable_login_settings', 'disable_login_settings');

		if (isset($_GET['tab']) && !is_numeric($_GET['tab'])){
			$active_tab = sanitize_text_field($_GET['tab']);
		} else {
			$active_tab = 'general';
		}

		$other->render_form($options, $active_tab);

	}

	/**
	 * Function that builds the options array for Plugin_Settings class.
	 *
	 * @since    1.1.1
	 */
	public function option_fields() {

		$options = array(
			'general' => apply_filters('disable_login_general_settings',
				array(
					'enable_plugin' => array(
						'id'   => 'enable_plugin',
						'label' => __('Enable Plugin:', $this->plugin_name),
						'type' => 'checkbox',
					),
                    'allowed_user' => array(
                        'id'   => 'allowed_user',
                        'label' => __('Allow only this user to login:', $this->plugin_name),
                        'type' => 'select',
	                    'options' => $this->get_available_users(),
	                    'desc' => __('Username should match yours otherwise you may be locked out permanantly!', $this->plugin_name)
		            )
				)
			)
		);
		return apply_filters('disable_login_settings_group', $options);

	}

	/**
	 * Function that displays upgrade notice.
	 *
	 * @since    1.0.2
	 */
	public function display_premium_version_available_notice() {
		if (!get_site_transient('disable_login_nag' )) {
	    ?>
	    <div class="updated settings-error notice is-dismissible disablelogin-dismiss">
	    	<p><strong><?php _e( 'Want more features', 'disable-login' ); ?>? <a href="http://endif.media/disable-login-premium"><?php _e( 'Upgrade to Disable Login Premium', 'disable-login' ); ?> &raquo;</a></strong></p>
	    </div>
	    <?php }
	}

	/**
	 * Function that handles time-lapse for notice display.
	 *
	 * @since    1.0.2
	 */
	public function set_premium_upgrade_notice_transient() {
		set_site_transient( 'disable_login_nag', 'true', 60 * 60 * 168); //7days 
	}

	/**
	 * @return string
	 * @since 1.1.1
	 */
	public function get_available_users() {

		$currentUser = wp_get_current_user();
        $users = get_users();

		foreach($users as $user) {
			$options[ $user->user_login ] = ($currentUser->user_login == $user->user_login ) ? $user->display_name . ' ( you )' : $user->display_name;
		}
		return $options;

	}

}