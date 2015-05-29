<?php
/**
 * This function converts $old meta_key entries in the postmeta table into $new entries.
 *
 * It first checks to see what records for the $new meta_key already exist,
 * storing the corresponding post_id values in an array. When the conversion
 * happens, rows that contain a post_id in that array will be ignored, to
 * avoid duplicate $new meta_key entries.
 *
 * The $old entries will be left as-is if $delete_old is left false. If set
 * to true, the $old entries will be deleted, rather than retained.
 *
 * The function returns an object for error detection, and the number of affected rows.
 */
function seodt_meta_key_convert( $old = '', $new = '', $delete_old = false ) {

	do_action( 'pre_seodt_meta_key_convert_before', $old, $new, $delete_old );
	
	global $wpdb;
	
	$output = new stdClass;
	
	if ( !$old || !$new ) {
		$output->WP_Error = 1;
		return $output;
	}

	// 	See which records we need to ignore, if any
	$exclude = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $new ) );

	//	If no records to ignore, we'll do a basic UPDATE and DELETE
	if ( !$exclude ) {
		
		$output->updated = $wpdb->update( $wpdb->postmeta, array( 'meta_key' => $new ), array( 'meta_key' => $old ) );
		$output->deleted = $delete_old ? $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $old ) ) : 0;
		$output->ignored = 0;
		
	} 
	//	Else, do a more complex UPDATE and DELETE
	else {
		
		foreach ( (array)$exclude as $key => $value ) {
			$not_in[] = $value->post_id;
		}
		$not_in = implode(', ', (array)$not_in );
		
		$output->updated = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key = %s AND post_id NOT IN ($not_in)", $new, $old ) );
		$output->deleted = $delete_old ? $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $old ) ) : 0;
		$output->ignored = count( $exclude );
		
	}

	do_action( 'seodt_meta_key_convert', $output, $old, $new, $delete_old );

	return $output;
	
}

/**
 * This function cycles through all compatible SEO entries of two platforms,
 * performs a seodt_meta_key_convert() conversion for each key, and returns
 * the results as an object.
 * 
 * It first checks for compatible entries between the two platforms. When it
 * finds compatible entries, it loops through them and preforms the conversion
 * on each entry.
 */
function seodt_post_meta_convert( $old_platform = '', $new_platform = '', $delete_old = false ) {

	do_action( 'pre_seodt_post_meta_convert', $old_platform, $new_platform, $delete_old );
	
	global $_seodt_platforms;
	
	$output = new stdClass;
	
	if ( empty( $_seodt_platforms[$old_platform] ) || empty( $_seodt_platforms[$new_platform] ) ) {
		$output->WP_Error = 1;
		return $output;
	}
	
	$output->updated = 0;
	$output->deleted = 0;
	$output->ignored = 0;
	
	foreach ( (array)$_seodt_platforms[$old_platform] as $label => $meta_key ) {
		
		// skip iterations where no $new analog exists
		if ( empty( $_seodt_platforms[$new_platform][$label] ) )
			continue;
		
		// set $old and $new meta_key values
		$old = $_seodt_platforms[$old_platform][$label];
		$new = $_seodt_platforms[$new_platform][$label];
		
		// convert
		$result = seodt_meta_key_convert( $old, $new, $delete_old );
		
		// error check
		if ( is_wp_error( $result ) )
			continue;
		
		// update total updated/ignored count
		$output->updated = $output->updated + (int)$result->updated;
		$output->ignored = $output->ignored + (int)$result->ignored;
		
	}

	do_action( 'seodt_post_meta_convert', $output, $old_platform, $new_platform, $delete_old );

	return $output;
		
}

/**
 * This function analyzes two platforms to see what Compatible elements they share,
 * what data can be converted from one to the other, and which elements to ignore (future).
 */
function seodt_post_meta_analyze( $old_platform = '', $new_platform = '' ) {

	do_action( 'pre_seodt_post_meta_analyze', $old_platform, $new_platform );
	
	global $wpdb, $_seodt_platforms;
	
	$output = new stdClass;
	
	if ( empty( $_seodt_platforms[$old_platform] ) || empty( $_seodt_platforms[$new_platform] ) ) {
		$output->WP_Error = 1;
		return $output;
	}
	
	$output->update = 0;
	$output->ignore = 0;
	$output->elements = '';
	
	foreach ( (array)$_seodt_platforms[$old_platform] as $label => $meta_key ) {
		
		// skip iterations where no $new analog exists
		if ( empty( $_seodt_platforms[$new_platform][$label] ) )
			continue;
			
		$elements[] = $label;
		
		// see which records to ignore, if any
		$ignore = 0;
//		$ignore = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) );
		
		// see which records to update, if any
		$update = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) );
		
		// count items in returned arrays
//		$ignore = count( (array)$ignore );
		$update = count( (array)$update );
		
		// calculate update/ignore by comparison
//		$update = ( (int)$update > (int)$ignore ) ? ( (int)$update - (int)$ignore ) : 0;
		
		// update output numbers
		$output->update = $output->update + (int)$update;
		$output->ignore = $output->ignore + (int)$ignore;
		
	} // endforeach
	
	$output->elements = $elements;

	do_action( 'seodt_post_meta_analyze', $output, $old_platform, $new_platform );

	return $output;
	
}