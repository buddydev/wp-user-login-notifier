<?php
/**
 * Login Notifier.
 */
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Login Notifier.
 */
class BuddyDev_User_Login_Notifier {
	/**
	 * Array of registered notifiers.
	 *
	 * @var BuddyDev_Login_Notifier[]
	 */
	private $notifiers = array();

	/**
	 * Singleton instance.
	 *
	 * @var BuddyDev_User_Login_Notifier
	 */
	private static $instance;

	/**
	 * BuddyDev_User_Login_Notifier constructor.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Factory method to get singleton instance.
	 *
	 * @return BuddyDev_User_Login_Notifier
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set a notifier.
	 *
	 * @param string                  $type unique identifier.
	 * @param BuddyDev_Login_Notifier $notifier notifier object.
	 *
	 * @return BuddyDev_User_Login_Notifier|null
	 */
	public function set_notifier( $type, $notifier ) {

		if ( ! $type ) {
			return null;
		}
		$type = sanitize_key( $type );

		$this->notifiers[ $type ] = $notifier;

		return $this;
	}

	/**
	 * Get the registered notifier.
	 *
	 * @param string $type notifier type.
	 *
	 * @return BuddyDev_Login_Notifier|null
	 */
	public function get_notifier( $type ) {

		if ( isset( $this->notifiers[ $type ] ) ) {
			return $this->notifiers[ $type ];
		}

		return null;
	}

	/**
	 * Get all notifiers.
	 *
	 * @return BuddyDev_Login_Notifier[]
	 */
	public function get_notifiers() {
		return $this->notifiers;
	}

	/**
	 * Initialize.
	 */
	private function init() {
		add_action( 'wp_login_failed', array( $this, 'notify_failed_login' ) );
		add_action( 'wp_login', array( $this, 'notify_login' ), 10, 2 );
	}

	/**
	 * Notify on login.
	 *
	 * @param string  $user_login user name.
	 * @param WP_User $user user object.
	 */
	public function notify_login( $user_login, $user ) {

		if ( isset( $this->notifiers['success'] ) && is_a( $this->notifiers['success'], 'BuddyDev_Login_Notifier' ) ) {

			if ( buddydev_wpuln_get_setting( 'notify_admin_on_success' ) === 'yes' ) {
				$this->notifiers['success']->notify_admin( $user );
			}

			if ( buddydev_wpuln_get_setting( 'notify_user_on_success' ) === 'yes' ) {
				$this->notifiers['success']->notify_user( $user );
			}
		}
	}

	/**
	 * Notify on failed login.
	 *
	 * @param string $user_login username.
	 */
	public function notify_failed_login( $user_login ) {

		$user = null;

		if ( username_exists( $user_login ) ) {
			$user = get_user_by( 'login', $user_login );
		}

		if ( ! $user ) {
			return;
		}

		if ( isset( $this->notifiers['fail'] ) && is_a( $this->notifiers['fail'], 'BuddyDev_Login_Notifier' ) ) {

			if ( buddydev_wpuln_get_setting( 'notify_admin_on_fail' ) === 'yes' ) {
				$this->notifiers['fail']->notify_admin( $user );
			}

			if ( buddydev_wpuln_get_setting( 'notify_user_on_fail' ) === 'yes' ) {
				$this->notifiers['fail']->notify_user( $user );
			}
		}
	}
}

$notification_manager = BuddyDev_User_Login_Notifier::get_instance();
// register the notifiers.
$notification_manager->set_notifier( 'fail', new BuddyDev_Failed_Login_Email_Notifier() );
$notification_manager->set_notifier( 'success', new BuddyDev_Successful_Login_Email_Notifier() );
