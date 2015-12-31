<?php
/**
 * CondoFees Conditional Functions
 *
 * Functions for determining the current query/page.
 *
 * @author      Sabiux
 * @category    Core
 * @package     CondoFees/Functions
 * @version     0.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @access public
	 * @return bool
	 */
	function is_ajax() {
		return defined( 'DOING_AJAX' );
	}
}

