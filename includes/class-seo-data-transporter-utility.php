<?php
/**
 * Data Transporter Class
 *
 * @package seo-data-transporter
 */

/**
 * The utility class.
 *
 * @since 1.0.0
 */
class SEO_Data_Transporter_Utility {

	/**
	 * Supported platforms.
	 *
	 * @var $platforms Platforms.
	 */
	private $platforms;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Array $platforms Platforms.
	 */
	public function __construct( $platforms ) {

		$this->platforms = $platforms;

	}

	/**
	 * Analyze two platforms for compatible records.
	 *
	 * @since 1.0.0
	 *
	 * @param Array $old_platform Old Platform.
	 * @param Array $new_platform New Platform.
	 */
	public function analyze( $old_platform = '', $new_platform = '' ) {

		do_action( 'pre_seodt_post_meta_analyze', $old_platform, $new_platform );

		global $wpdb;

		$output = new stdClass();

		// Neither platform should be empty.
		if ( empty( $this->platforms[ $old_platform ] ) || empty( $this->platforms[ $new_platform ] ) ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$output->WP_Error = 1;
			return $output;
		}

		$output->update   = 0;
		$output->ignore   = 0;
		$output->elements = array();

		foreach ( (array) $this->platforms[ $old_platform ] as $label => $meta_key ) {

			// Skip iterations where no $new analog exists.
			if ( empty( $this->platforms[ $new_platform ][ $label ] ) ) {
				continue;
			}

			$output->elements[] = $label;

			// See which records to ignore, if any.
			$ignore = 0;
			// See which records to update, if any.
			// phpcs:ignore
			$update = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) );

			// Count items in returned arrays.
			$update = count( (array) $update );

			// calculate update/ignore by comparison
			// Update output numbers.
			$output->update = $output->update + (int) $update;
			$output->ignore = $output->ignore + (int) $ignore;

		} // endforeach

		// Deprecated.
		do_action( 'seodt_post_meta_analyze', $output, $old_platform, $new_platform );

		return $output;

	}

	/**
	 * Convert compatible records from two platforms.
	 *
	 * @since 1.0.0
	 *
	 * @param Array $old_platform Old Platform.
	 * @param Array $new_platform New Platform.
	 * @param Array $delete_old Delete Old.
	 */
	public function convert( $old_platform = '', $new_platform = '', $delete_old = false ) {

		do_action( 'pre_seodt_post_meta_convert', $old_platform, $new_platform, $delete_old );

		$output = new stdClass();

		if ( empty( $this->platforms[ $old_platform ] ) || empty( $this->platforms[ $new_platform ] ) ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$output->WP_Error = 1;
			return $output;
		}

		$output->updated = 0;
		$output->deleted = 0;
		$output->ignored = 0;

		foreach ( (array) $this->platforms[ $old_platform ] as $label => $meta_key ) {

			// Skip iterations where no $new analog exists.
			if ( empty( $this->platforms[ $new_platform ][ $label ] ) ) {
				continue;
			}

			// Set $old and $new meta_key values.
			$old_key = $this->platforms[ $old_platform ][ $label ];
			$new_key = $this->platforms[ $new_platform ][ $label ];

			// Convert.
			$result = $this->meta_key_convert( $old_key, $new_key, $delete_old );

			// Error check.
			if ( is_wp_error( $result ) ) {
				continue;
			}

			// Update total updated/ignored count.
			$output->updated = $output->updated + (int) $result->updated;
			$output->ignored = $output->ignored + (int) $result->ignored;

		}

		do_action( 'seodt_post_meta_convert', $output, $old_platform, $new_platform, $delete_old );

		return $output;

	}

	/**
	 * Convert meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @param Array $old_key Old Key.
	 * @param Array $new_key New Key.
	 * @param Array $delete_old Delete Old.
	 */
	public function meta_key_convert( $old_key = '', $new_key = '', $delete_old = false ) {

		do_action( 'pre_seodt_meta_key_convert_before', $old_key, $new_key, $delete_old );

		global $wpdb;

		$output = new stdClass();

		if ( ! $old_key || ! $new_key ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$output->WP_Error = 1;
			return $output;

		}

		// See which records we need to ignore, if any.
		// phpcs:ignore
		$exclude = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $new_key ) );

		// If no records to ignore, we'll do a basic UPDATE and DELETE.
		if ( ! $exclude ) {

			// phpcs:ignore
			$output->updated = $wpdb->update( $wpdb->postmeta, array( 'meta_key' => $new_key ), array( 'meta_key' => $old_key ) );
			$output->deleted = 0;
			$output->ignored = 0;
		} else {
			// Else, do a more complex UPDATE and DELETE.
			foreach ( (array) $exclude as $key => $value ) {
				$not_in[] = $value->post_id;
			}
			$not_in = implode( ', ', (array) $not_in );

			// phpcs:ignore
			$output->updated = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key = %s AND post_id NOT IN (%s)", $new_key, $old_key, $not_in ) );
			$output->deleted = 0;
			$output->ignored = count( $exclude );

		}

		do_action( 'seodt_meta_key_convert', $output, $old_key, $new_key, $delete_old );

		return $output;

	}

}
