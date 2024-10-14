<?php
/**
 * Main Data Transporter Class
 *
 * @package seo-data-transporter
 */

define( 'SEO_DATA_TRANSPORTER_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEO_DATA_TRANSPORTER_URL', plugins_url( '', __FILE__ ) );
define( 'SEO_DATA_TRANSPORTER_VERSION', '1.1.2' );

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

/**
 * Initialize checking of plugin updates from WP Engine.
 */
function seo_data_transporter_check_for_upgrades() {
	$properties = array(
		'plugin_slug'     => 'seo-data-transporter',
		// phpcs:ignore
		'plugin_basename' => plugin_basename( dirname( __FILE__ ) . '/plugin.php' ),
	);

	require_once __DIR__ . '/includes/class-seo-data-transporter-plugin-updater.php';
	new SEO_Data_Transporter_Plugin_Updater( $properties );
}
add_action( 'admin_init', 'seo_data_transporter_check_for_upgrades' );
