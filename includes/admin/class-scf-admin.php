<?php
/**
 * CondoFees Admin.
 *
 * @class       SC_Admin
 * @author      Sabiux
 * @category    Admin
 * @package     CondoFees/Admin
 * @version     0.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SC_Admin class.
 */
class SCF_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		//add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
		//add_action( 'admin_init', array( $this, 'prevent_admin_access' ) );
		//add_action( 'admin_init', array( $this, 'preview_emails' ) );
		//add_action( 'admin_footer', 'wc_print_js', 25 );
		//add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// Functions
		include_once( 'scf-admin-functions.php' );
		//include_once( 'wc-meta-box-functions.php' );

		// Classes
		//include_once( 'class-wc-admin-post-types.php' );
		//include_once( 'class-wc-admin-taxonomies.php' );

		// Classes we only need during non-ajax requests
		if ( ! is_ajax() ) {
			include_once( 'class-scf-admin-menus.php' );
			//include_once( 'class-wc-admin-welcome.php' );
			//include_once( 'class-wc-admin-notices.php' );
			//include_once( 'class-wc-admin-assets.php' );
			//include_once( 'class-wc-admin-webhooks.php' );

			// Help
			//if ( apply_filters( 'woocommerce_enable_admin_help_tab', true ) ) {
			//	include_once( 'class-wc-admin-help.php' );
			//}
		}

		// Importers
		//if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
		//	include_once( 'class-wc-admin-importers.php' );
		//}
	}

}

return new SCF_Admin();
