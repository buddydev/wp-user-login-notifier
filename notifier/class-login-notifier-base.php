<?php
//It acts as an interface for the notification service provider
abstract class BuddyDev_Login_Notifier {
	
	
	abstract public function notify_admin( $user ) ;
	abstract public function notify_user( $user );
	
	
	/**
	 * Get extra details about the visitors(user trying to login)
	 * 
	 * @staticvar array $extra
	 * @return array ( 'ip'=> '', 'client'=>'', 'site_name'=> '', 'time=> '')
	 */
	protected function get_extra() {
		//we are storing the result in a static variable to avoid recalculating it.
		// Normaly, In a single request, these information will never change, so why rcalculate
		static $extra;

		if( isset( $extra ) )
			return $extra;

		if( ! class_exists( 'Browser' ) ) {
			
			require_once BUDDYDEV_WPULN_PLUGIN_DIR . 'lib/Browser.php';
			
		}
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ip			= $this->get_ip();//preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] );
		$client		= isset( $user_agent ) ? substr( $user_agent, 0, 254 ) : '';
		
		$referer	= wp_get_referer();
		
		$site_name	= get_bloginfo('name');
		
		$time = current_time( 'Y:m:d H:g:i' );

		$browser_detector = new Browser( $user_agent );
		
		$platform = $browser_detector->getPlatform();
		$browser = $browser_detector->getBrowser();
		
		$extra = compact( 'ip', 'client', 'site_name', 'referer', 'time', 'platform', 'browser' );

		return $extra;
	}
	/**
	 * Thank you BuddyPress team for the code of 'bp_get_email_subject'
	 * I kind of like the way message subject have site name listed
	 * 
	 * @param type $args
	 * @return type
	 */
	protected function get_email_subject( $args = array() ) {

		$r = wp_parse_args( $args, array(
			'before'  => '[',
			'after'   => ']',
			'default' => __( 'Your Blog', 'wp-user-login-notifier' ),
			'text'    => ''
		) );

		$subject = $r['before'] . wp_specialchars_decode( get_option( 'blogname', $r['default'] ), ENT_QUOTES ) . $r['after'] . ' ' . $r['text'];

		return  $subject;
	}
	/**
	 * Try to find the ip of the client
	 * 
	 * @return type
	 */
	private function get_ip() {
		
		$ip_address = '';
		
		if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		} elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
			
		} else {
			
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip_address;
	}
}

