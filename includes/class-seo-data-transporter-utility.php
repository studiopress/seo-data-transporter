<?php
/**
 * The utility class.
 *
 * @since 1.0.0
 */
 class SEO_Data_Transporter_Utility {

	/**
	 * Supported platforms.
	 */
	private $platforms;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $platforms ) {

		$this->platforms = $platforms;

	}

	/**
	* Analyze two platforms for compatible records.
	*
	* @since 1.0.0
	*/
	public function analyze( $old_platform = '', $new_platform = '' ) {

		do_action( 'pre_seodt_post_meta_analyze', $old_platform, $new_platform );

		global $wpdb;

		$output = new stdClass;

		// Neither platform should be empty.
		if ( empty( $this->platforms[ $old_platform ] ) || empty( $this->platforms[ $new_platform ] ) ) {
			$output->WP_Error = 1;
			return $output;
		}

		$output->update   = 0;
		$output->ignore   = 0;
		$output->elements = array();

		foreach ( (array) $this->platforms[ $old_platform ] as $label => $meta_key ) {

			// skip iterations where no $new analog exists
			if ( empty( $this->platforms[ $new_platform ][ $label ] ) ) {
				continue;
			}

			$output->elements[] = $label;

			// see which records to ignore, if any
			$ignore = 0;
	//		$ignore = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) );

			// see which records to update, if any
			$update = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) );

			// count items in returned arrays
	//		$ignore = count( (array)$ignore );
			$update = count( (array) $update );

			// calculate update/ignore by comparison
	//		$update = ( (int)$update > (int)$ignore ) ? ( (int)$update - (int)$ignore ) : 0;

			// update output numbers
			$output->update = $output->update + (int) $update;
			$output->ignore = $output->ignore + (int) $ignore;

		} // endforeach

		// Deprecated
		do_action( 'seodt_post_meta_analyze', $output, $old_platform, $new_platform );

		return $output;

	}

	/**
	 * Convert compatible records from two platforms.
	 *
	 * @since 1.0.0
	 */
	public function convert( $old_platform = '', $new_platform = '', $delete_old = false ) {

		do_action( 'pre_seodt_post_meta_convert', $old_platform, $new_platform, $delete_old );

		$output = new stdClass;

		if ( empty( $this->platforms[ $old_platform ] ) || empty( $this->platforms[ $new_platform ] ) ) {
			$output->WP_Error = 1;
			return $output;
		}

		$output->updated = 0;
		$output->deleted = 0;
		$output->ignored = 0;

		foreach ( (array) $this->platforms[ $old_platform ] as $label => $meta_key ) {

			// skip iterations where no $new analog exists
			if ( empty( $this->platforms[ $new_platform ][ $label ] ) ) {
				continue;
			}

			// set $old and $new meta_key values
			$old_key = $this->platforms[ $old_platform ][ $label ];
			$new_key = $this->platforms[ $new_platform ][ $label ];

			// convert
			$result = $this->meta_key_convert( $old_key, $new_key, $delete_old );

			// error check
			if ( is_wp_error( $result ) ) {
				continue;
			}

			// update total updated/ignored count
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
	 */
	public function meta_key_convert( $old_key = '', $new_key = '', $delete_old = false ) {

		do_action( 'pre_seodt_meta_key_convert_before', $old_key, $new_key, $delete_old );

		global $wpdb;

		$output = new stdClass;

		if ( ! $old_key || ! $new_key ) {

			$output->WP_Error = 1;
			return $output;

		}

		// 	See which records we need to ignore, if any
		$exclude = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $new_key ) );

		//	If no records to ignore, we'll do a basic UPDATE and DELETE
		if ( ! $exclude ) {

			$output->updated = $wpdb->update( $wpdb->postmeta, array( 'meta_key' => $new_key ), array( 'meta_key' => $old_key ) );
			//$output->deleted = $delete_old ? $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $old_key ) ) : 0;
			$output->deleted = 0;
			$output->ignored = 0;

		}
		//	Else, do a more complex UPDATE and DELETE
		else {

			foreach ( (array) $exclude as $key => $value ) {
				$not_in[] = $value->post_id;
			}
			$not_in = implode( ', ', (array) $not_in );

			$output->updated = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key = %s AND post_id NOT IN ($not_in)", $new_key, $old_key ) );
			//$output->deleted = $delete_old ? $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $old_key ) ) : 0;
			$output->deleted = 0;
			$output->ignored = count( $exclude );

		}

		do_action( 'seodt_meta_key_convert', $output, $old_key, $new_key, $delete_old );

		return $output;

	}

 }
