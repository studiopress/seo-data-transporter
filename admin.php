<?php
/**
 * Register the admin menu page
 */
add_action('admin_menu', 'seodt_settings_init');
function seodt_settings_init() {
	global $_seodt_admin_pagehook;
	
	// Add submenu page link
	$_seodt_admin_pagehook = add_submenu_page('tools.php', __('SEO Data Transport','seodt'), __('SEO Data Transport','seodt'), 'manage_options', 'seodt', 'seodt_admin');
}

/**
 * This function intercepts POST data from the form submission, and uses that
 * data to convert values in the postmeta table from one platform to another.
 */
function seodt_action() {
	
	//print_r($_REQUEST);
	
	if ( empty( $_REQUEST['_wpnonce'] ) )
		return;
	
	if ( empty( $_REQUEST['platform_old'] ) || empty( $_REQUEST['platform_new'] ) ) {
		printf( '<div class="error"><p>%s</p></div>', __('Sorry, you can\'t do that. Please choose two different platforms.') );
		return;
	}
		
	if ( $_REQUEST['platform_old'] == $_REQUEST['platform_new'] ) {
		printf( '<div class="error"><p>%s</p></div>', __('Sorry, you can\'t do that. Please choose two different platforms.') );
		return;
	}
		
	check_admin_referer('seodt'); // Verify nonce
	
	if ( !empty( $_REQUEST['analyze'] ) ) {
		
		printf( '<h3>%s</h3>', __('Analysis Results', 'seodt') );
		
		$response = seodt_post_meta_analyze( $_REQUEST['platform_old'], $_REQUEST['platform_new'] );
		if ( is_wp_error( $response ) ) {
			printf( '<div class="error"><p>%s</p></div>', __('Sorry, something went wrong. Please try again') );
			return;
		}
		
		printf( __('<p>Analyzing records in a %s to %s conversion&hellip;', 'seodt'), esc_html( $_POST['platform_old'] ), esc_html( $_POST['platform_new'] ) );
		printf( '<p><b>%d</b> Compatible Records were identified</p>', $response->update );
//		printf( '<p>%d Compatible Records will be ignored</p>', $response->ignore );
		
		printf( '<p><b>%s</b></p>', __('Compatible elements:', 'seodt') );
		echo '<ol>';
		foreach ( (array)$response->elements as $element ) {
			printf( '<li>%s</li>', $element );
		}
		echo '</ol>';
		
		return;
	}
	
	printf( '<h3>%s</h3>', __('Conversion Results', 'seodt') );
	
	$result = seodt_post_meta_convert( $_REQUEST['platform_old'], $_REQUEST['platform_new'] );
	if ( is_wp_error( $result ) ) {
		printf( '<p>%s</p>', __('Sorry, something went wrong. Please try again') );
		return;
	}
	
	printf( '<p><b>%d</b> Records were updated</p>', $result->updated );
	printf( '<p><b>%d</b> Records were ignored</p>', $result->ignored );
	
	return;
	
}

/**
 * This function displays feedback to the user about compatible conversion
 * elements and the conversion process via the admin_alert hook.
 */

/**
 * The admin page output
 */
function seodt_admin() {
	global $_seodt_themes, $_seodt_plugins, $_seodt_platforms;
?>

	<div class="wrap">
		
	<?php screen_icon('tools'); ?>
	<h2><?php _e('SEO Data Transporter', 'seodt'); ?></h2>
	
	<p><span class="description"><?php printf( __('Use the form below to choose which platform you wish to convert FROM, and which platform you wish to convert TO.', 'seodt') ); ?></span></p>
	
	<p><span class="description"><?php printf( __('Click "Analyze" for a list of elements you are able to convert, along with the number of records that will be converted. Some platforms do not share similar elements, or store data in a non-standard way. These records will remain unchanged. Any compatible elements will be displayed for your review. Also, some records will be ignored if the post/page in question already contains a record for that particular SEO element in the new platform.', 'seodt') ); ?></span></p>
	
	<p><span class="description"><?php printf( __('Click "Convert" to perform the conversion. After the conversion is complete, you will be alerted to how many records were converted, and how many records had to be ignored, based on the criteria above.', 'seodt') ); ?></span></p>
		
	<form action="<?php echo admin_url('tools.php?page=seodt'); ?>" method="post">
	<?php
		wp_nonce_field('seodt');
	
		_e('Convert inpost SEO data from:', 'seodt');
		echo '<select name="platform_old">';
		printf( '<option value="">%s</option>', __('Choose platform:', 'seodt') );
		
		printf( '<optgroup label="%s">', __('Themes', 'seodt') );
		foreach ( $_seodt_themes as $platform => $data ) {
			printf( '<option value="%s" %s>%s</option>', $platform, selected($platform, $_POST['platform_old'], 0), $platform );
		}
		printf( '</optgroup>' );
		
		printf( '<optgroup label="%s">', __('Plugins', 'seodt') );
		foreach ( $_seodt_plugins as $platform => $data ) {
			printf( '<option value="%s" %s>%s</option>', $platform, selected($platform, $_POST['platform_old'], 0), $platform );
		}
		printf( '</optgroup>' );
		
		echo '</select>' . "\n\n";
		
		_e('to:', 'seodt');
		echo '<select name="platform_new">';
		printf( '<option value="">%s</option>', __('Choose platform:', 'seodt') );
		
		printf( '<optgroup label="%s">', __('Themes', 'seodt') );
		foreach ( $_seodt_themes as $platform => $data ) {
			printf( '<option value="%s" %s>%s</option>', $platform, selected($platform, $_POST['platform_new'], 0), $platform );
		}
		printf( '</optgroup>' );
		
		printf( '<optgroup label="%s">', __('Plugins', 'seodt') );
		foreach ( $_seodt_plugins as $platform => $data ) {
			printf( '<option value="%s" %s>%s</option>', $platform, selected($platform, $_POST['platform_new'], 0), $platform );
		}
		printf( '</optgroup>' );
		
		
		echo '</select>' . "\n\n";
	?>
	
	<input type="submit" class="button-highlighted" name="analyze" value="<?php _e('Analyze', 'genesis'); ?>" />
	<input type="submit" class="button-primary" value="<?php _e('Convert', 'genesis') ?>" />
	
	</form>
	
	<?php seodt_action(); ?>
	
	</div>

<?php	
}