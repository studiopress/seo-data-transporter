<?php

class SEO_Data_Transporter_CLI extends WP_CLI_Command {

	/**
	 * Convert compatible records between two platforms.
	 *
	 * ## OPTIONS
	 *
	 * <old_platform>
	 * : The platform you want to convert from.
	 *
	 * [<new_platform>]
	 * : The platform you want to convert to.
	 *
	 * ## COMPATIBLE platforms
	 *
	 * Themes:
	 * * Builder
	 * * Catalyst
	 * * Frugal
	 * * Genesis
	 * * Headway
	 * * Hybrid
	 * * Thesis 1.x
	 * * WooFramework
	 *
	 * Plugins:
	 * * Add Meta Tags
	 * * All in One SEO Pack
	 * * Greg's High Performance SEO
	 * * Headspace2
	 * * Infinite SEO
	 * * Jetpack Advanced SEO
	 * * Meta SEO Pack
	 * * Platinum SEO
	 * * SEO Title Tag
	 * * SEO Ultimate
	 * * Yoast SEO
	 *
	 * ## EXAMPLES
	 *
	 *     # Convert compatible records from Hybrid to All in One SEO Pack
	 *     $ wp seodt convert "Hybrid" "All in One SEO Pack"
	 *     Success: X Records were successfully converted.
	 */
	public function convert( $args, $assoc_args ) {

		list( $old_platform, $new_platform ) = $args;

		$platforms = SEO_Data_Transporter()->get_supported_platforms();

		if ( ! array_key_exists( $old_platform, $platforms ) || ! array_key_exists( $new_platform, $platforms ) ) {
			WP_CLI::error( __( 'This plugin does not support one or both of the platforms you chose.', 'seo-data-transporter' ) );
			return false;
		}

		if ( $old_platform == $new_platform ) {
			WP_CLI::error( __( 'You must choose two different platforms before submitting.', 'seo-data-transporter' ) );
			return false;
		}

		require_once( SEO_Data_Transporter()->plugin_dir_path . 'includes/class-seo-data-transporter-utility.php' );
		$utility = new SEO_Data_Transporter_Utility( $platforms );

		$analysis = $utility->analyze( $old_platform, $new_platform );

		if ( is_wp_error( $analysis ) ) {
			WP_CLI::error( __( 'Something went wrong. Please try again.', 'seo-data-transporter' ) );
			return false;
		}

		if ( 0 == $analysis->update ) {
			WP_CLI::line( __( 'No compatible records were identified.', 'seo-data-transporter' ) );
			return false;
		}

		WP_CLI::confirm( sprintf( __( '%d compatible records identified. Continue with conversion?', 'seo-data-transporter' ), $analysis->update ) );

		$result = $utility->convert( $old_platform, $new_platform );

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( __( 'Something went wrong. Please try again.', 'seo-data-transporter' ) );
			return false;
		}

		if ( 0 == $result->updated ) {
			WP_CLI::line( __( 'No records could be changed.', 'seo-data-transporter' ) );
			return false;
		}

		WP_CLI::success( sprintf( __( '%d records were successfully converted.', 'seo-data-transporter' ), $result->updated ) );

	}

}
