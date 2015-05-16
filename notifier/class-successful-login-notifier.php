<?php
//notify successful login by email
class BuddyDev_Successful_Login_Email_Notifier extends BuddyDev_Login_Notifier {
	
	
	public function notify_admin( $user ) {
		
		
		$email = get_option('admin_email');

		$user_login = $user->user_login;
		
		if( $user->user_email == $email )
			return ;// don't send admin two messages 
		
		$details = $this->get_extra() ;
		extract( $details );
		
		$subject_append = '';
		
		if( $platform && $browser  ){
			
			$subject_append = __( ' from %s on %s', 'wp-user-login-notifier' );
			
			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		
		$subject = __( 'New sign-in notification for {%s}', 'wp-user-login-notifier');
		
		$subject = sprintf( $subject, $user_login );
			
		$subject = $subject . $subject_append; 
			
		$subject = $this->get_email_subject( array( 'text' => $subject ) );
		
		//should we rellay say congratulations, it's debatable and listen folks, I neeed help to decide here.
		$message = 'Hi,
Congratulations, A user just logged into your site %1$s.

Details:-
		
User: %2$s
IP: %3$s
Browser: %4$s
Platform: %5$s
Referer: %6$s,
Time: %7$s
User Agent: %8$s

';
	



	$message = sprintf( $message, $site_name, $user_login, $ip, $browser, $platform, $referer, $time, $client );	

	wp_mail( $email, $subject, $message );
	
	}
	
	public function notify_user ( $user ) {

		$user_login = $user->user_login;
		$email = $user->user_email;
		
		$details = $this->get_extra() ;
		extract( $details );
		
		$subject_append = '';
		
		if( $platform && $browser  ){
			
			$subject_append = __( ' from %s on %s', 'wp-user-login-notifier' );
			
			$subject_append = sprintf( $subject_append, $browser, $platform );
		}
		
		$subject = __( 'New sign-in from your account {%s}', 'wp-user-login-notifier');
		
		$subject = sprintf( $subject, $user_login );
	
		$subject = $subject . $subject_append;
		
		$subject = $this->get_email_subject( array( 'text' => $subject ) );
		
		$message = 'Hi %1$s,
Your %2$s account %3$s was just used to sign in from %4$s on %5$s.

Details:-

IP: %6$s
Browser: %4$s
Platform: %5$s
Referer %7$s,
Time: %8$s
User Agent: %9$s
If it was you, just ignore this email. If it looks suspicious, Please change your password and notify the site admin.
Thank you.

%2$s Team
%10$s
';
	

	


	$message = sprintf( $message, $user->display_name,  $site_name, $user_login, $browser, $platform,  $ip,  $referer, $time, $client, get_option( 'url' ) );	

	wp_mail( $email, $subject, $message );	
	}
}