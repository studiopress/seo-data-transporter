<?php
/*
Plugin Name:	SEO Data Transporter
Plugin URI:		http://www.studiopress.com/plugins/seo-data-transporter
Description:	Helps you transfer post/page specific SEO data, like custom doctitles, custom META descriptions and keywords, etc., from one platform (theme or plugin) to another.
Version:		0.9.3
Author:			Nathan Rice
Author URI:		http://www.nathanrice.net/
*/

define('SEODT_PLUGIN_DIR', dirname(__FILE__));

/**
 * The associative array of supported themes.
 */
$_seodt_themes = array(
	// alphabatized
	'Builder' => array(
		'Custom Doctitle' => '_builder_seo_title',
		'META Description' => '_builder_seo_description',
		'META Keywords' => '_builder_seo_keywords',
	),
	'Frugal' => array(
		'Custom Doctitle' => '_title',
		'META Description' => '_description',
		'META Keywords' => '_keywords',
		'noindex' => '_noindex',
		'nofollow' => '_nofollow'	
	),
	'Genesis' => array(
		'Custom Doctitle' => '_genesis_title',
		'META Description' => '_genesis_description',
		'META Keywords' => '_genesis_keywords',
		'noindex' => '_genesis_noindex',
		'nofollow' => '_genesis_nofollow',
		'noarchive' => '_genesis_noarchive',
		'Canonical URI' => '_genesis_canonical_uri',
		'Custom Scripts' => '_genesis_scripts',
		'Redirect URI' => 'redirect'
	),
	'Headway' => array(
		'Custom Doctitle' => '_title',
		'META Description' => '_description',
		'META Keywords' => '_keywords'
	),
	'Hybrid' => array(
		'Custom Doctitle' => 'Title',
		'META Description' => 'Description',
		'META Keywords' => 'Keywords'
	),
	'Thesis' => array(
		'Custom Doctitle' => 'thesis_title',
		'META Description' => 'thesis_description',
		'META Keywords' => 'thesis_keywords',
		'Custom Scripts' => 'thesis_javascript_scripts',
		'Redirect URI' => 'thesis_redirect',
	),
	'WooFramework' => array(
		'Custom Doctitle' => 'seo_title',
		'META Description' => 'seo_description',
		'META Keywords' => 'seo_keywords'
	)
);

/**
 * The associative array of supported plugins.
 */
$_seodt_plugins = array(
	// alphabatized
	'All in One SEO Pack' => array(
		'Custom Doctitle' => '_aioseop_title',
		'META Description' => '_aioseop_description',
		'META Keywords' => '_aioseop_keywords',
	),
	'Headspace2' => array(
		'Custom Doctitle' => '_headspace_page_title',
		'META Description' => '_headspace_description',
		'META Keywords' => '_headspace_keywords',
		'Custom Scripts' => '_headspace_scripts'
	),
	'Platinum SEO' => array(
		'Custom Doctitle' => 'title',
		'META Description' => 'description',
		'META Keywords' => 'keywords',
	),
	'WordPress SEO' => array(
		'Custom Doctitle' => '_yoast_seo_title',
		'META Description' => '_yoast_seo_metadesc',
		'noindex' => '_yoast_wpseo_meta-robots-noindex',
		'nofollow' => '_yoast_wpseo_meta-robots-nofollow',
		'Canonical URI' => '_yoast_wpseo_canonical',
		'Redirect URI' => '_yoast_wpseo_redirect'
	)
);

/**
 * The combined array of supported platforms.
 */
$_seodt_platforms = array_merge( $_seodt_themes, $_seodt_plugins );

/**
 * Include the other elements of the plugin.
 */
require_once(SEODT_PLUGIN_DIR . '/admin.php');
require_once(SEODT_PLUGIN_DIR . '/functions.php');

/**
 * Manual conversion test
 */
/*
$seodt_convert = seodt_post_meta_convert( 'All in One SEO Pack', 'Genesis', false );
printf( '%d records were updated', $seodt_convert->updated );
/**/

/**
 * Manual analysis test
 */
/*
$seodt_analyze = seodt_post_meta_analyze( 'All in One SEO Pack', 'Genesis' );
printf( '<p><b>%d</b> Compatible Records were identified</p>', $seodt_analyze->update );
/**/

/**
 * Delete all SEO data, from every platform
 */
/*
foreach ( $_seodt_platforms as $platform => $data ) {
	
	foreach ( $data as $field ) {
		$deleted = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $field ) );
		printf( '%d %s records deleted<br />', $deleted, $field );
	}
	
}
/**/

/**
 * Query all SEO data to find the number of records to change
 */
/*
foreach ( $_seodt_platforms as $platform => $data ) {
	
	foreach ( $data as $field ) {
		$update = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $field ) );
		printf( '%d %s records can be updated<br />', count( $update ), $field );
		//print_r($update);
	}
	
}
/**/