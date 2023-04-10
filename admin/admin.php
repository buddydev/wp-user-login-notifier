<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// we use options buddy.
require_once dirname( __FILE__ ) . '/options-buddy/ob-loader.php';

/**
 * Admin settings.
 */
class WP_User_Login_Notifier_Admin {
	/**
	 * Page instance.
	 *
	 * @var OptionsBuddy_Settings_Page
	 */
	private $page;

	/**
	 * Page slug.
	 *
	 * @var string
	 */
	private $slug = 'wp-user-login-notifier';

	/**
	 * Constructor.
	 */
	public function __construct() {

		// create a options page
		// make sure to read the code below.
		$this->page = new OptionsBuddy_Settings_Page( 'wp_user_login_notifier_settings' );

		//$this->page->set_network_mode();//make it to use bp_get_option/bp_update_option

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_footer', array( $this, 'admin_css' ) );
	}

	/**
	 * Initialize admin settings.
	 */
	public function admin_init() {

		// set the settings.
		$page = $this->page;
		// add_section.
		$page->add_section( 'basic_section', __( 'WP User Login Notifier', 'wp-user-login-notifier' ) );

		// add fields.
		$page->get_section( 'basic_section' )->add_fields(
			array(
				array(
					'name'    => 'notify_admin_on_fail',
					'label'   => __( 'Mail admin on login Failure?', 'wp-user-login-notifier' ),
					'desc'    => __( 'Admin will get notification when a user tries to login with wrong password.', 'wp-user-login-notifier' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-user-login-notifier' ),
						'no'  => __( 'No', 'wp-user-login-notifier' ),
					),
				),
				array(
					'name'    => 'notify_user_on_fail',
					'label'   => __( 'Notify User on Login failure?', 'wp-user-login-notifier' ),
					'desc'    => __( 'If you enable, The User will get notification when te login attempt from their account fails.', 'wp-user-login-notifier' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-user-login-notifier' ),
						'no'  => __( 'No', 'wp-user-login-notifier' ),
					),
				),
				array(
					'name'    => 'notify_admin_on_success',
					'label'   => __( 'Notify admin on successful login?', 'wp-user-login-notifier' ),
					'desc'    => __( 'Admin will get notification when a user logs in to the site successfully.', 'wp-user-login-notifier' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-user-login-notifier' ),
						'no'  => __( 'No', 'wp-user-login-notifier' ),
					),
				),
				array(
					'name'    => 'notify_user_on_success',
					'label'   => __( 'Notify User on successful login?', 'wp-user-login-notifier' ),
					'desc'    => __( 'If you enable, The User will get notification when they login successfully to the site.', 'wp-user-login-notifier' ),
					'type'    => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'wp-user-login-notifier' ),
						'no'  => __( 'No', 'wp-user-login-notifier' ),
					),
				),
				array(
					'name'    => 'notify_extra_users',
					'label'   => __( 'Notify More Users?', 'wp-user-login-notifier' ),
					'desc'    => __( 'Please add a comma separated list of email you want to be notified.', 'wp-user-login-notifier' ),
					'type'    => 'textarea',
					'default' => '',
				),
			)
		);

		$page->init();
	}

	/**
	 * Adds admin menu.
	 */
	public function admin_menu() {
		add_options_page(
			__( 'WP User Login Notifer', 'wp-user-login-notifier' ),
			__( 'WP User Login Notifier', 'wp-user-login-notifier' ),
			'manage_options',
			$this->slug,
			array( $this->page, 'render' )
		);
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function admin_css() {

		if ( ! isset( $_GET['page'] ) || $_GET['page'] != $this->slug ) {
			return;
		}

		?>
        <style type="text/css">
            .wrap .form-table {
                margin: 10px;
            }

        </style>
		<?php
	}
}

new WP_User_Login_Notifier_Admin();
