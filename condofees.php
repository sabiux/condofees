<?php
/**
 * Plugin Name: CondoFees
 * Plugin URI: http://www.sabiux.com/condofees/
 * Description: Permite el manejo del recibo de gastos de un condominio. 
 * Version: 0.0.0-dev
 * Author: Sabiux
 * Author URI: http://www.sabiux.com
 *
 * Text Domain: condofees
 * Domain Path: /includes/translations
 *
 * Requires at least: 4.0
 * License: GNU General Public License
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! class_exists( 'CondoFees' ) ) :

/**
 * Main CondoFees Class
 *
 * @class CondoFees
 * @version	0.0.0
 */
final class CondoFees {

	/**
	 * @var CondoFees The single instance of the class
	 * @since 0.0.0
	 */
	protected static $_instance = null;



	/**
	 * Main CondoFees Instance
	 *
	 * Ensures only one instance of CondoFees is loaded or can be loaded.
	 *
	 * @since 0.0.0
	 * @static
	 * @see CondoFees()
	 * @return CondoFees - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 0.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'condofees' ), '0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 0.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'condofees' ), '0.0' );
	}


	/**
	 * CondoFees Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'condofees_loaded' );
	}

	private function define_constants() {
		$upload_dir = wp_upload_dir();

		$this->define( 'SCF_PLUGIN_FILE', __FILE__ );
		$this->define( 'SCF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'SCF_VERSION', $this->version );
		$this->define( 'SCF_LOG_DIR', $upload_dir['basedir'] . '/scf-logs/' );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	public function includes() {
		include_once( 'includes/scf-core-functions.php' );
		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-scf-admin.php' );
		}
	}

	private function init_hooks() {
//		register_activation_hook( __FILE__, array( 'WC_Install', 'install' ) );
//		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
//		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
		add_action( 'init', array( $this, 'init' ), 0 );
//		add_action( 'init', array( 'WC_Shortcodes', 'init' ) );
//		add_action( 'init', array( 'WC_Emails', 'init_transactional_emails' ) );
	}

	public function init() {
		// Before init action
		do_action( 'before_condofees_init' );

		// Set up localisation
		$this->load_plugin_textdomain();

/*		// Load class instances
		$this->product_factory = new WC_Product_Factory();                      // Product Factory to create new product instances
		$this->order_factory   = new WC_Order_Factory();                        // Order Factory to create new order instances
		$this->countries       = new WC_Countries();                            // Countries class
		$this->integrations    = new WC_Integrations();                         // Integrations class

		// Classes/actions loaded for the frontend and for ajax requests
		if ( $this->is_request( 'frontend' ) ) {
			// Session class, handles session data for users - can be overwritten if custom handler is needed
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );

			// Class instances
			$this->session  = new $session_class();
			$this->cart     = new WC_Cart();                                    // Cart class, stores the cart contents
			$this->customer = new WC_Customer();                                // Customer class, handles data such as customer location
		}

		$this->load_webhooks();
*/
		// Init action
		do_action( 'condofees_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Admin Locales are found in:
	 * 		- WP_LANG_DIR/condofees/condofees-admin-LOCALE.mo
	 * 		- WP_LANG_DIR/plugins/condofees-admin-LOCALE.mo
	 *
	 * Frontend/global Locales found in:
	 * 		- WP_LANG_DIR/condofees/condofees-LOCALE.mo
	 * 	 	- condofees/i18n/languages/condofees-LOCALE.mo (which if not found falls back to:)
	 * 	 	- WP_LANG_DIR/plugins/condofees-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'condofees' );

		if ( $this->is_request( 'admin' ) ) {
			load_textdomain( 'condofees', WP_LANG_DIR . '/condofees/condofees-admin-' . $locale . '.mo' );
			load_textdomain( 'condofees', WP_LANG_DIR . '/plugins/condofees-admin-' . $locale . '.mo' );
		}

		load_textdomain( 'condofees', WP_LANG_DIR . '/condofees/condofees-' . $locale . '.mo' );
		load_plugin_textdomain( 'condofees', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n/languages" );

	}



	/**
	 * Fix `$_SERVER` variables for various setups.
	 *
	 * Note: Removed IIS handling due to wp_fix_server_vars()
	 *
	 * @since 0.0.0
	 */
	private function fix_server_vars() {
		// NGINX Proxy
		if ( ! isset( $_SERVER['REMOTE_ADDR'] ) && isset( $_SERVER['HTTP_REMOTE_ADDR'] ) ) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_REMOTE_ADDR'];
		}

		if ( ! isset( $_SERVER['HTTPS'] ) ) {
			if ( ! empty( $_SERVER['HTTP_HTTPS'] ) ) {
				$_SERVER['HTTPS'] = $_SERVER['HTTP_HTTPS'];
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
				$_SERVER['HTTPS'] = '1';
			}
		}
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}


	/**
	 * Get Ajax URL.
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}
}

endif;

/**
 * Returns the main instance of SCF to prevent the need to use globals.
 *
 * @since  0.0.0
 * @return CondoFees
 */
function SCF() {
	return CondoFees::instance();
}

// Global for backwards compatibility.
$GLOBALS['condofees'] = SCF();

?>
