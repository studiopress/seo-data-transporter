<?php
/**
 * Deprecated functions.
 *
 * @since 1.0.0
 */

/**
 * Deprecated. Initialize the SEO Data Transporter plugin.
 *
 * @deprecated 1.0.0
 */
function seodt_init() {

	_deprecated_function( __FUNCTION__, '1.0.0', 'SEO_Data_Transporter::init()' );

}

/**
 * Deprecated. Activation Hook.
 *
 * @deprecated 1.0.0
 */
function seodt_activation_hook() {

	_deprecated_function( __FUNCTION__, '1.0.0' );

}

/**
 * Deprecated. This function converts $old meta_key entries in the postmeta table into $new entries.
 *
 * @deprecated 1.0.0
 */
function seodt_meta_key_convert( $old_key, $new_key, $delete_old ) {

	_deprecated_function( __FUNCTION__, '1.0.0', 'SEO_Data_Transporter_Utility::meta_key_convert()' );

	$utility = SEO_Data_Transporter_Utility( SEO_Data_Transporter()->get_supported_platforms() );

	return $utility->meta_key_convert( $old_key, $new_key, $delete_old );

}

/**
 * Deprecated.
 *
 * @deprecated 1.0.0
 */
function seodt_post_meta_convert( $old_platform, $new_platform, $delete_old ) {

	_deprecated_function( __FUNCTION__, '1.0.0', 'SEO_Data_Transporter_Utility::convert()' );

	$utility = SEO_Data_Transporter_Utility( SEO_Data_Transporter()->get_supported_platforms() );

	return $utility->convert( $old_platform, $new_platform, $delete_old );

}

/**
 * Deprecated.
 *
 * @deprecated 1.0.0
 */
function seodt_post_meta_analyze( $old_platform, $new_platform ) {

	_deprecated_function( __FUNCTION__, '1.0.0', 'SEO_Data_Transporter_Utility::convert()' );

	$utility = SEO_Data_Transporter_Utility( SEO_Data_Transporter()->get_supported_platforms() );

	return $utility->analyze( $old_platform, $new_platform, $delete_old );

}

/**
 * Deprecated. Register the admin menu page.
 *
 * @deprecated 1.0.0
 */
function seodt_settings_init() {

	_deprecated_function( __FUNCTION__, '1.0.0' );

}

/**
 * Deprecated. This function intercepts POST data from the form submission, and uses that
 * data to convert values in the postmeta table from one platform to another.
 *
 * @deprecated 1.0.0
 */
function seodt_action() {

	_deprecated_function( __FUNCTION__, '1.0.0' );

}

/**
 * Deprecated. The admin page output
 *
 * @deprecated 1.0.0
 */
function seodt_admin() {

	_deprecated_function( __FUNCTION__, '1.0.0' );

}
