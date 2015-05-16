<?php
//failed login, notify by email
class BuddyDev_Failed_Login_Email_Notifier extends BuddyDev_Login_Notifier {
	
	
	public function notify_admin( $user ) {
		
		$user_login = $user->user_login;
		
		$email = get_option('admin_email');
		
		$details = $this->get_extra() ;
		
		extract( $details );
	
		$subject_append = '';
		
		if( $platform && $browser  ){
			
			$subject_append = __( ' from %s on %s', 'wp-user-login-notifier' );
			
			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		$subject = __( 'Login failed for user {%s}', 'wp-user-login-notifier');
		
		$subject = sprintf( $subject, $user_login );
		$subject = $subject . $subject_append; 
		
		$subject = $this->get_email_subject( array( 'text' => $subject ) );
	
		$message = __('Hi,
There was a failed login attempt on your site %1$s.

Details:

User: %2$s
IP: %3$s
Browser: %4$s
Platform: %5$s
Referer: %6$s,
Time: %7$s
User Agent: %8$s

No need to worry or panic if this is just the first email. 
If you are getting it repeatedly, Please make sure that your user accounts are secured properly.

You may want to install some WordPress security plugin to tighten the security.
Recommendation: https://wordpress.org/plugins/tags/security
', 'wp-user-login-notifier' );
	
	$message = sprintf( $message, $site_name, $user_login, $ip, $browser, $platform, $referer, $time, $client );	

	wp_mail( $email, $subject, $message );
	
	}
	
	public function notify_user ( $user ) {
	
		$email = $user->user_email;
		
		$user_login = $user->user_login;
		
		$details = $this->get_extra() ;
		extract( $details );

		$subject_append = '';
		
		if( $platform && $browser  ){
			
			$subject_append = __( ' from %s on %s', 'wp-user-login-notifier' );
			
			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		
		$subject = __( 'Login failed for your account {%s}', 'wp-user-login-notifier');
		
		$subject = sprintf( $subject, $user_login );
		
		$subject = $subject . $subject_append; 
		
		$subject = $this->get_email_subject( array( 'text' => $subject ) );
		
		$message = __( 'An attemp to login from your account [%1$s] on site %2$s failed.
			
Access Details:-

IP: %3$s
Browser: %4$s
Platform: %5$s
Referer %6$s
Time: %7$s
User Agent: %8$s
Please make sure that you are using a secure password. if not, you should change it right now and inform the site administrator.

%2$s Team
%9$s

', 'wp-user-login-notifier');
	


	$message = sprintf( $message, $user_login, $site_name,  $ip, $browser, $platform,  $referer, $time, $client, get_option( 'url') );	

	wp_mail( $email, $subject, $message );	
	}
}