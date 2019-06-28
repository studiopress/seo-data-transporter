<?php
/**
 * Main Data Transporter Class
 *
 * @package seo-data-transporter
 */

define( 'SEO_DATA_TRANSPORTER_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEO_DATA_TRANSPORTER_URL', plugins_url( '', __FILE__ ) );
define( 'SEO_DATA_TRANSPORTER_VERSION', '1.1.0' );

require_once SEO_DATA_TRANSPORTER_DIR . '/includes/class-seo-data-transporter.php';

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 1.0.0
 */
function seo_data_transporter() {

	static $object;

	if ( null === $object ) {
		$object = new SEO_Data_Transporter();
	}

	return $object;

}

/**
 * Initialize the object on `plugins_loaded`.
 */
add_action( 'plugins_loaded', array( SEO_Data_Transporter(), 'init' ) );
