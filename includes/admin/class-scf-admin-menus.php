<?php
/**
 * Setup menus in WP admin.
 *
 * @author      Sabiux
 * @category    Admin
 * @package     CondoFees/Admin
 * @version     0.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SCF_Admin_Menus' ) ) :

/**
 * SCF_Admin_Menus Class
 */
class SCF_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'proprietor_menu' ), 20 );
		add_action( 'admin_menu', array( $this, 'apartment_menu' ), 50 );
	}


	/**
	 * Add menu items
	 */
	public function admin_menu() {
		global $menu;

//		if ( current_user_can( 'manage_condofees' ) ) {
			$menu[] = array( '', 'read', 'separator-condofees', '', 'wp-menu-separator condofees' );
//		}

		add_menu_page( __( 'Condominio', 'condofees' ), __( 'Condominio', 'condofees' ), 'read', 'condofees', null, null, '55.5' );

		add_submenu_page( 'edit.php?post_type=product', __( 'Shipping Classes', 'condofees' ), __( 'Shipping Classes', 'condofees' ), 'read', 'edit-tags.php?taxonomy=product_shipping_class&post_type=product' );

		add_submenu_page( 'edit.php?post_type=product', __( 'Attributes', 'condofees' ), __( 'Attributes', 'condofees' ), 'read', 'product_attributes', array( $this, 'attributes_page' ) );
	}

	/**
	 * Add menu item
	 */
	public function proprietor_menu() {
//		if ( current_user_can( 'manage_woocommerce' ) ) {
			add_submenu_page( 'condofees', __( 'Reports', 'condofees' ),  __( 'Reports', 'condofees' ) , 'read', 'wc-reports', array( $this, 'reports_page' ) );
//		} else {
//			add_menu_page( __( 'Sales Reports', 'condofees' ),  __( 'Sales Reports', 'condofees' ) , 'read', 'wc-reports', array( $this, 'reports_page' ), null, '55.6' );
//		}
	}

	/**
	 * Add menu item
	 */
	public function apartment_menu() {
		$settings_page = add_submenu_page( 'condofees', __( 'WooCommerce Settings', 'condofees' ),  __( 'Settings', 'condofees' ) , 'read', 'wc-settings', array( $this, 'settings_page' ) );

		add_action( 'load-' . $settings_page, array( $this, 'settings_page_init' ) );
	}

	/**
	 * Init the settings page
	 */
	public function settings_page() {
//		WC_Admin_Settings::output();
	}

	/**
	 * Loads gateways and shipping methods into memory for use within settings.
	 */
	public function settings_page_init() {
	}

	/**
	 * Add menu item
	 */
	public function status_menu() {
	}

	/**
	 * Addons menu item
	 */
	public function addons_menu() {
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 */
	public function menu_highlight() {
	}

	/**
	 * Adds the order processing count to the menu
	 */
	public function menu_order_count() {
	}

	/**
	 * Reorder the SCF menu items in admin.
	 *
	 * @param mixed $menu_order
	 * @return array
	 */
	public function menu_order( $menu_order ) {
	}

	/**
	 * Custom menu order
	 *
	 * @return bool
	 */
	public function custom_menu_order() {
	}
}

endif;

return new SCF_Admin_Menus();
