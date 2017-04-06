<?php
/**
 * The SEO Data Transporter main class.
 *
 * @since 1.0.0
 */
final class SEO_Data_Transporter {

	/**
	 * Plugin version
	 */
	public $plugin_version = '1.0.0';

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain = 'seo-data-transporter';

	/**
	 * The url to the plugin directory.
	 */
	public $plugin_dir_url;

	/**
	 * The path to the plugin directory.
	 */
	public $plugin_dir_path;

	/**
	 * Supported themes.
	 */
	public $themes;

	/**
	 * Supported plugins.
	 */
	public $plugins;

	/**
	 * Admin object.
	 */
	public $admin;

	/**
	 * Utility object
	 */
	public $utility;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );

		// For backward compatibility
		define( 'SEODT_PLUGIN_DIR', $this->plugin_dir_path );

		$this->themes = array(
			'Builder' => array(
				'Custom Doctitle'  => '_builder_seo_title',
				'META Description' => '_builder_seo_description',
				'META Keywords'    => '_builder_seo_keywords',
			),
			'Catalyst' => array(
				'Custom Doctitle'  => '_catalyst_title',
				'META Description' => '_catalyst_description',
				'META Keywords'    => '_catalyst_keywords',
				'noindex'          => '_catalyst_noindex',
				'nofollow'         => '_catalyst_nofollow',
				'noarchive'        => '_catalyst_noarchive',
			),
			'Frugal' => array(
				'Custom Doctitle'  => '_title',
				'META Description' => '_description',
				'META Keywords'    => '_keywords',
				'noindex'          => '_noindex',
				'nofollow'         => '_nofollow',
			),
			'Genesis' => array(
				'Custom Doctitle'  => '_genesis_title',
				'META Description' => '_genesis_description',
				'META Keywords'    => '_genesis_keywords',
				'noindex'          => '_genesis_noindex',
				'nofollow'         => '_genesis_nofollow',
				'noarchive'        => '_genesis_noarchive',
				'Canonical URI'    => '_genesis_canonical_uri',
				'Custom Scripts'   => '_genesis_scripts',
				'Redirect URI'     => 'redirect',
			),
			'Headway' => array(
				'Custom Doctitle'  => '_title',
				'META Description' => '_description',
				'META Keywords'    => '_keywords',
			),
			'Hybrid' => array(
				'Custom Doctitle'  => 'Title',
				'META Description' => 'Description',
				'META Keywords'    => 'Keywords',
			),
			'Thesis 1.x' => array(
				'Custom Doctitle'  => 'thesis_title',
				'META Description' => 'thesis_description',
				'META Keywords'    => 'thesis_keywords',
				'Custom Scripts'   => 'thesis_javascript_scripts',
				'Redirect URI'     => 'thesis_redirect',
			),
			'WooFramework' => array(
				'Custom Doctitle'  => 'seo_title',
				'META Description' => 'seo_description',
				'META Keywords'    => 'seo_keywords',
			),
		);

		$this->plugins = array(
			'Add Meta Tags' => array(
				'Custom Doctitle'  => '_amt_title',
				'META Description' => '_amt_description',
				'META Keywords'    => '_amt_keywords',
			),
			'All in One SEO Pack' => array(
				'Custom Doctitle'  => '_aioseop_title',
				'META Description' => '_aioseop_description',
				'META Keywords'    => '_aioseop_keywords',
			),
			'Greg\'s High Performance SEO' => array(
				'Custom Doctitle'  => '_ghpseo_secondary_title',
				'META Description' => '_ghpseo_alternative_description',
				'META Keywords'    => '_ghpseo_keywords',
			),
			'Headspace2' => array(
				'Custom Doctitle'  => '_headspace_page_title',
				'META Description' => '_headspace_description',
				'META Keywords'    => '_headspace_keywords',
				'Custom Scripts'   => '_headspace_scripts',
			),
			'Infinite SEO' => array(
				'Custom Doctitle'  => '_wds_title',
				'META Description' => '_wds_metadesc',
				'META Keywords'    => '_wds_keywords',
				'noindex'          => '_wds_meta-robots-noindex',
				'nofollow'         => '_wds_meta-robots-nofollow',
				'Canonical URI'    => '_wds_canonical',
				'Redirect URI'     => '_wds_redirect',
			),
			'Jetpack Advanced SEO' => array(
				'META Description' => 'advanced_seo_description',
			),
			'Meta SEO Pack' => array(
				'META Description' => '_msp_description',
				'META Keywords'    => '_msp_keywords',
			),
			'Platinum SEO' => array(
				'Custom Doctitle'  => 'title',
				'META Description' => 'description',
				'META Keywords'    => 'keywords',
			),
			'SEO Title Tag' => array(
				'Custom Doctitle'  => 'title_tag',
				'META Description' => 'meta_description',
			),
			'SEO Ultimate' => array(
				'Custom Doctitle'  => '_su_title',
				'META Description' => '_su_description',
				'META Keywords'    => '_su_keywords',
				'noindex'          => '_su_meta_robots_noindex',
				'nofollow'         => '_su_meta_robots_nofollow',
			),
			'Yoast SEO' => array(
				'Custom Doctitle'  => '_yoast_wpseo_title',
				'META Description' => '_yoast_wpseo_metadesc',
				'META Keywords'    => '_yoast_wpseo_metakeywords',
				'noindex'          => '_yoast_wpseo_meta-robots-noindex',
				'nofollow'         => '_yoast_wpseo_meta-robots-nofollow',
				'Canonical URI'    => '_yoast_wpseo_canonical',
				'Redirect URI'     => '_yoast_wpseo_redirect',
			),
		);

	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->load_plugin_textdomain();

		$this->includes();
		$this->instantiate();

		/**
		 * Init hook.
		 *
		 * Hook fires after plugin functions are loaded.
		 *
		 * @since 0.9.10
		 */
		do_action( 'seodt_init' );

	}

	/**
	 * Load the plugin textdomain, for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . 'languages/' );
	}

	/**
	 * Includes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		require_once( $this->plugin_dir_path . 'includes/deprecated.php' );

	}

	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 1.0.0
	 */
	public function instantiate() {

		/**
		 * The admin page.
		 */
		require_once( $this->plugin_dir_path . 'includes/class-seo-data-transporter-admin.php' );
		$this->admin = new SEO_Data_Transporter_Admin( $this->themes, $this->plugins );
		$this->admin->init();

		/**
		 * The CLI commands.
		 */
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once( $this->plugin_dir_path . 'includes/class-seo-data-transporter-cli.php' );
			WP_CLI::add_command( 'seodt', 'SEO_Data_Transporter_CLI' );
		}

	}

	/**
	 * Return array of supported themes.
	 *
	 * @since 1.0.0
	 */
	public function get_supported_themes() {
		return $this->themes;
	}

	/**
	 * Return array of supported plugins.
	 *
	 * @since 1.0.0
	 */
	public function get_supported_plugins() {
		return $this->plugins;
	}

	/**
	 * Return combined array of supported themes and plugins.
	 *
	 * @since 1.0.0
	 */
	public function get_supported_platforms() {
		return array_merge( $this->themes, $this->plugins );
	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 1.0.0
 */
function SEO_Data_Transporter() {

	static $object;

	if ( null == $object ) {
		$object = new SEO_Data_Transporter;
	}

	return $object;

}

/**
 * Initialize the object on	`plugins_loaded`.
 */
add_action( 'plugins_loaded', array( SEO_Data_Transporter(), 'init' ) );
