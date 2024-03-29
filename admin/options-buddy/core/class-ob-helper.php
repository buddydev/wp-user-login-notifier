<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'OptionsBuddy_Helper' ) ):
	/**
	 * Settings Page store
	 * Use it to add/retrieve settings pages
	 * Not required to make the script function but just a convenienence
	 */
	class OptionsBuddy_Helper {

		/**
		 * Url of the current directory
		 *
		 * @var string
		 */
		private $url;

		/**
		 * Singleton instance.
		 *
		 * @var OptionsBuddy_Helper
		 */
		private static $instance;

		/**
		 * Pages array.
		 *
		 * @var OptionsBuddy_Settings_Page
		 */
		private $pages = array();

		/**
		 * Constructor.
		 */
		private function __construct() {
			if ( ! isset( $this->url ) ) {
				// we need to find the directory of the options-buddy
				// it could be inside a theme or a plugin we  don't know.
				$path = dirname( __FILE__ );

				// for windows.
				$path = str_replace( '\\', '/', $path );

				$abspath = str_replace( '\\', '/', ABSPATH );

				// find relative path.
				$rel_path = str_replace( $abspath, '', $path );

				$this->url = trailingslashit( site_url( '/' ) . $rel_path );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'load_js' ) );
		}

		/**
		 * Retrieves singleton instance.
		 *
		 * @return OptionsBuddy_Helper
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add Page.
		 *
		 * @param string $page_name the slug for page
		 * @param OptionsBuddy_Settings_Page $page
		 *
		 * @return OptionsBuddy_Settings_Page
		 */
		public function add_page( $page_name, $page = false ) {

			if ( ! $page ) {
				$page = new OptionsBuddy_Settings_Page( $page_name );
			}

			$this->pages[ $page_name ] = $page;
			return $page;
		}

		/**
		 * Retrieves page instance.
		 *
		 * @param string $page_name the slug for page.
		 *
		 * @return OptionsBuddy_Settings_Page
		 */
		public function get_page( $page_name ) {
			// if the page exists in the store, let us return it.
			if ( isset( $this->pages[ $page_name ] ) ) {
				return $this->pages[ $page_name ];
			}

			// otherwise return a new page.
			return $this->add_page( $page_name );
		}

		/**
		 * Enqueues scripts and styles
		 */
		public function load_js() {
			wp_enqueue_media();
			wp_enqueue_script( 'optionsbuddy-media-uploader', $this->url . '_inc/uploader.js', array( 'jquery' ) );
		}
	}

	OptionsBuddy_Helper::get_instance();
endif;