<?php
/**
 * Plugin Name: WP User Login Notifier
 * Version: 1.0.3
 * Plugin URI: https://buddyDev.com/plugins/wp-user-login-notifier/
 * Author: BuddyDev
 * Author URI: https://buddydev.com
 * Description: Notify User/Admin when a users logs in or fails to log in
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}
// plugin dir path.
define( 'BUDDYDEV_WPULN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// I am sacrificing good looking class name in favor of non conflicting name,
// WP_User_Login_Notifier might have been 100 times better if not 1000 times.
/**
 * This is a helper class,
 * all it does is to load other files
 * and add default option on activation.
 * Implemented as singleton though not required to do so
 */
class BuddyDev_User_Login_Notification_Helper {
	/**
	 * Singleton instance.
	 *
	 * @var BuddyDev_User_Login_Notification_Helper
	 */
	private static $instance;

	/**
	 * Path to the current plugin directory
	 *
	 * @var string
	 */
	private $path;

	/**
	 * BuddyDev_User_Login_Notification_Helper constructor.
	 */
	private function __construct() {
		// filesystem path to this plugin directory.
		$this->path = BUDDYDEV_WPULN_PLUGIN_DIR;

		// also hook the loader on init action.
		// we are hooking to init as logged in user is initialized at this point
		// if you are looking at the ode, you should use plugins_loaded instead of init unless you understand what you are doing.
		add_action( 'init', array( $this, 'load' ), 0 );
		add_action( 'init', array( $this, 'load_textdomain' ), 1 );

		register_activation_hook(
			__FILE__,
			array(
				'BuddyDev_User_Login_Notification_Helper',
				'on_activation',
			)
		);
		register_uninstall_hook( __FILE__, array( 'BuddyDev_User_Login_Notification_Helper', 'on_uninstall' ) );
	}

	/**
	 * Get the singleton Instance
	 *
	 * @return BuddyDev_User_Login_Notification_Helper
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load required files.
	 */
	public function load() {

		$files = array();
		// load the notifiers if the user is not logged in
		// why waste processing time and memory because if a user is logged in, we don't need the notifier.
		if ( ! is_user_logged_in() ) {

			$files = array(
				'functions.php',
				'notifier/class-login-notifier-base.php',
				'notifier/class-failed-login-notifier.php',
				'notifier/class-successful-login-notifier.php',
				'notifier/class-user-login-notifier.php',
			);

		} elseif ( is_admin() && is_user_logged_in() && ! defined( 'DOING_AJAX' ) ) {
			// if it is admin, let us load the admin settings code.
			$files = array( 'admin/admin.php' );
		}


		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}
	}

	/**
	 * Load translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-user-login-notifier', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Add the default options on activation
	 */
	public static function on_activation() {

		if ( ! get_option( 'wp_user_login_notifier_settings' ) ) {

			$default = array(
				'notify_admin_on_fail'    => 'yes',
				'notify_admin_on_success' => 'yes',
				'notify_user_on_fail'     => 'yes',
				'notify_user_on_success'  => 'yes',
			);

			add_option( 'wp_user_login_notifier_settings', $default );
		}
	}

	/**
	 * Delete the option/settings when this plugin in uninstalled
	 */
	public static function on_uninstall() {

		delete_option( 'wp_user_login_notifier_settings' );
	}

}

// instantiate.
BuddyDev_User_Login_Notification_Helper::get_instance();
