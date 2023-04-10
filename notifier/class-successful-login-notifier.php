<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Successful login email notifier.
 */
class BuddyDev_Successful_Login_Email_Notifier extends BuddyDev_Login_Notifier {

	/**
	 * Notify admin.
	 *
	 * @param WP_User $user user object.
	 */
	public function notify_admin( $user ) {

		$details = $this->get_extra();

		$email = apply_filters( 'wpuln_successful_login_notifiable_admin_email', get_option( 'admin_email' ), $user, $details );

		$user_login = $user->user_login;

		if ( ! is_array( $email ) && $user->user_email === $email ) {
			return;// don't send admin two messages.
		}
		// user link.
		$link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $user->ID ) : add_query_arg( 'user_id', $user->ID, self_admin_url( 'user-edit.php' ) );

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

		$subject = __( 'New sign-in notification for {%s}', 'wp-user-login-notifier' );

		$subject = sprintf( $subject, $user_login );

		$subject = $subject . $subject_append;

		$subject = apply_filters( 'wpuln_successful_login_admin_email_subject', $this->get_email_subject( array( 'text' => $subject ) ), $user, $details );

		// should we really say congratulations, it's debatable. I need help to decide here.
		$message = 'Hi,
Congratulations, A user just logged into your site %1$s.

Details:-
		
User:       %2$s
Link:       %9$s
IP:         %3$s
Browser:    %4$s
Platform:   %5$s
Referer:    %6$s
Time:       %7$s
User Agent: %8$s

';

		$message = sprintf( $message, $site_name, $user_login, esc_html( $ip ), esc_html( $browser ), esc_html( $platform ), esc_html( $referer ), $time, esc_html( $client ), esc_url( $link ) );
		$message = apply_filters( 'wpuln_successful_login_admin_email_message', $message, $user, $details );

		$bcc_headers = apply_filters( 'wpuln_successful_login_admin_email_headers', buddydev_wpuln_get_bcc_header(), $user, $details );

		wp_mail( $email, $subject, $message, $bcc_headers );
	}

	/**
	 * Notify user.
	 *
	 * @param WP_User $user user object.
	 */
	public function notify_user( $user ) {
		$details = $this->get_extra();

		$user_login = $user->user_login;
		$email      = apply_filters( 'wpuln_successful_login_notifiable_user_email', $user->user_email, $user, $details );

		$platform  = $details['platform'];
		$browser   = $details['browser'];
		$client    = $details['client'];
		$ip        = $details['ip'];
		$referer   = $details['referer'];
		$time      = $details['time'];
		$site_name = $details['site_name'];

		$subject_append = '';

		if ( $platform && $browser ) {
			/* translators: 1: browser, 2: platform */
			$subject_append = __( ' from %1$s on %2$s', 'wp-user-login-notifier' );

			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		/* translators: %s: user login*/
		$subject = __( 'New sign-in from your account {%s}', 'wp-user-login-notifier' );

		$subject = sprintf( $subject, $user_login );

		$subject = $subject . $subject_append;

		$subject = $this->get_email_subject( array( 'text' => $subject ) );
		$subject = apply_filters( 'wpuln_successful_login_user_email_subject', $subject, $user, $details );
		$message = 'Hi %1$s,
Your %2$s account %3$s was just used to sign in from %4$s on %5$s.

Details:-

IP:         %6$s
Browser:    %4$s
Platform:   %5$s
Referer     %7$s
Time:       %8$s
User Agent: %9$s
If it was you, just ignore this email. If it looks suspicious, Please change your password and notify the site admin.
Thank you.

%2$s Team
%10$s
';

		$message = sprintf( $message, $user->display_name, $site_name, $user_login, esc_html( $browser ), esc_html( $platform ), esc_html( $ip ), esc_html( $referer ), $time, esc_html( $client ), get_option( 'url' ) );
		$message = apply_filters( 'wpuln_successful_login_user_email_message', $message, $user, $details );

		$headers = apply_filters( 'wpuln_successful_login_user_email_headers', array(), $user, $details );
		wp_mail( $email, $subject, $message, $headers );
	}
}
