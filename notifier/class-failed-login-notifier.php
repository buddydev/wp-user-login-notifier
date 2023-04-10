<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Failed login email notifier.
 */
class BuddyDev_Failed_Login_Email_Notifier extends BuddyDev_Login_Notifier {

	/**
	 * Notify admin about the user login failure.
	 *
	 * @param WP_User $user user object.
	 */
	public function notify_admin( $user ) {

		$user_login = $user->user_login;
		// user link.
		$link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $user->ID ) : add_query_arg( 'user_id', $user->ID, self_admin_url( 'user-edit.php' ) );

		$details = $this->get_extra();

		$email = apply_filters( 'wpuln_failed_login_notifiable_admin_email', get_option( 'admin_email' ), $user, $details );

		$platform  = $details['platform'];
		$browser   = $details['browser'];
		$client    = $details['client'];
		$ip        = $details['ip'];
		$referer   = $details['referer'];
		$time      = $details['time'];
		$site_name = $details['site_name'];

		$subject_append = '';

		if ( $platform && $browser ) {
			$subject_append = __( ' from %s on %s', 'wp-user-login-notifier' );
			$subject_append = sprintf( $subject_append, $browser, $platform );
		}

		$subject = __( 'Login failed for user {%s}', 'wp-user-login-notifier' );

		$subject = sprintf( $subject, $user_login );
		$subject = $subject . $subject_append;

		$subject = apply_filters( 'wpuln_failed_login_admin_email_subject', $this->get_email_subject( array( 'text' => $subject ) ), $user, $details );

		$message = __( 'Hi,
There was a failed login attempt on your site %1$s.

Details:

User:       %2$s
Link:       %9$s
IP:         %3$s
Browser:    %4$s
Platform:   %5$s
Referer:    %6$s
Time:       %7$s
User Agent: %8$s

No need to worry or panic if this is just the first email. 
If you are getting it repeatedly, Please make sure that your user accounts are secured properly.

You may want to install some WordPress security plugin to tighten the security.
Recommendation: https://wordpress.org/plugins/tags/security
', 'wp-user-login-notifier' );

		$message = sprintf( $message, $site_name, $user_login, esc_html( $ip ), esc_html( $browser ), esc_html( $platform ), esc_html( $referer ), $time, esc_html( $client ), $link );

		$message     = apply_filters( 'wpuln_failed_login_admin_email_message', $message, $user, $details );
		$bcc_headers = apply_filters( 'wpuln_failed_login_admin_email_headers', buddydev_wpuln_get_bcc_header(), $user, $details );

		wp_mail( $email, $subject, $message, $bcc_headers );
	}

	/**
	 * Notify user of failed login.
	 *
	 * @param WP_User $user user object.
	 */
	public function notify_user( $user ) {
		$details = $this->get_extra();

		$user_login = $user->user_login;

		$email = apply_filters( 'wpuln_failed_login_notifiable_user_email', $user->user_email, $user, $details );

		$platform  = $details['platform'];
		$browser   = $details['browser'];
		$client    = $details['client'];
		$ip        = $details['ip'];
		$referer   = $details['referer'];
		$time      = $details['time'];
		$site_name = $details['site_name'];

		$subject_append = '';

		if ( $platform && $browser ) {
			/* translators: 1: Browser, 2: Os */
			$subject_append = __( ' from %1$s on %2$s', 'wp-user-login-notifier' );

			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		/* translators: %s: user name */
		$subject = __( 'Login failed for your account {%s}', 'wp-user-login-notifier' );

		$subject = sprintf( $subject, $user_login );

		$subject = $subject . $subject_append;

		$subject = apply_filters( 'wpuln_failed_login_user_email_subject', $this->get_email_subject( array( 'text' => $subject ) ), $user, $details );
		/* translators: 1: user name, 2: site name, 3: IP, 4: Browser, 5: OS, 6: Referer, 7: Time, 8: User agent, 9: site url */
		$message = __( 'An attempt to login from your account [%1$s] on site %2$s failed.
			
Access Details:-

IP:         %3$s
Browser:    %4$s
Platform:   %5$s
Referer     %6$s
Time:       %7$s
User Agent: %8$s
Please make sure that you are using a secure password. if not, you should change it right now and inform the site administrator.

%2$s Team
%9$s

', 'wp-user-login-notifier' );

		$message = sprintf( $message, $user_login, $site_name, esc_html( $ip ), esc_html( $browser ), esc_html( $platform ), esc_html( $referer ), $time, esc_html( $client ), get_option( 'url' ) );
		$message = apply_filters( 'wpuln_failed_login_user_email_message', $message, $user, $details );
		$headers = apply_filters( 'wpuln_failed_login_user_email_headers', array(), $user, $details );
		wp_mail( $email, $subject, $message, $headers );
	}
}
