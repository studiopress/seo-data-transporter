<?php
/**
 * The admin page class.
 *
 * @since 1.0.0
 */
class SEO_Data_Transporter_Admin {

	/**
	 * The pagehook, identifying the page elsewhere.
	 *
	 * @since 1.0.0
	 */
	public $pagehook;

	/**
	 * Supported themes.
	 */
	private $themes;

	/**
	 * Supported plugins.
	 */
	private $plugins;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $themes = array(), $plugins = array() ) {

		$this->themes  = $themes;
		$this->plugins = $plugins;

	}

	/**
	 * Set up the admin page.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->page_id = 'seodt';

		$this->menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'tools.php',
				'page_title'  => __( 'SEO Data Transporter', 'seo-data-transporter' ),
				'menu_title'  => __( 'Convert SEO Data', 'seo-data-transporter' ),
			),
		);

		add_action( 'admin_menu', array( $this, 'create' ) );

	}

	/**
	 * Create the admin page.
	 *
	 * @since 1.0.0
	 */
	public function create() {

		$menu = wp_parse_args(
			$this->menu_ops['submenu'],
			array(
				'parent_slug' => '',
				'page_title'  => '',
				'menu_title'  => '',
				'capability'  => 'manage_options',
			)
		);

		$this->pagehook = add_submenu_page( $menu['parent_slug'], $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, array( $this, 'admin' ) );

		// If we need to load scripts for this page
		//add_action( "load-{$this->pagehook}", array( $this, 'scripts' ) );

		add_action( "load-{$this->pagehook}", array( $this, 'process_form' ) );

	}

	/**
	 * The admin page view.
	 *
	 * @since 1.0.0
	 */
	public function admin() {

		require_once( SEO_Data_Transporter()->plugin_dir_path . 'includes/views/admin.php' );

	}

	/**
	 * Generate the select dropdown using $themes and $plugins.
	 *
	 * @since 1.0.0
	 */
	public function generate_select( $name, $themes, $plugins ) {

		printf( '<select name="%s">', esc_attr( $name ) );
		printf( '<option value="">%s</option>', __( 'Choose platform:', 'seo-data-transporter' ) );

		printf( '<optgroup label="%s">', __('Themes', 'seo-data-transporter') );
		foreach ( $themes as $platform => $data ) {
			printf( '<option value="%s">%s</option>', esc_attr( $platform ), esc_html( $platform ) );
		}
		echo '</optgroup>';

		printf( '<optgroup label="%s">', __('Themes', 'seo-data-transporter') );
		foreach ( $plugins as $platform => $data ) {
			printf( '<option value="%s">%s</option>', esc_attr( $platform ), esc_html( $platform ) );
		}
		echo '</optgroup>';
		echo '</select>';

	}

	/**
	 * Process the form.
	 *
	 * @since 1.0.0
	 */
	public function process_form() {

		// Verify something from our form was submitted
		if ( empty( $_REQUEST['platform_old'] ) ) {
			return false;
		}

		check_admin_referer( 'seo-data-transporter' );

		$args = wp_parse_args( $_REQUEST, array(
			'analyze'      => 0,
			'platform_old' => '',
			'platform_new' => '',
		) );

		if ( ! $args['platform_old'] || ! $args['platform_new'] || $args['platform_old'] === $args['platform_new'] ) {
			add_action( 'admin_notices', array( $this, 'notice_error_select' ) );
			return false;
		}

		// Utility object
		require_once( SEO_Data_Transporter()->plugin_dir_path . 'includes/class-seo-data-transporter-utility.php' );
		$utility = new SEO_Data_Transporter_Utility( array_merge( $this->themes, $this->plugins ) );

		if ( $args['analyze'] ) {

			$this->analysis_result = $utility->analyze( $args['platform_old'], $args['platform_new'] );

			if ( is_wp_error( $this->analysis_result ) ) {
				add_action( 'admin_notices', array( $this, 'notice_error_unspecified' ) );
				return false;
			}

			add_action( 'admin_notices', array( $this, 'notice_success_analyze' ) );
			return true;

		}

		$this->conversion_result = $utility->convert( $args['platform_old'], $args['platform_new'] );

		if ( is_wp_error( $this->conversion_result ) ) {
			add_action( 'admin_notices', array( $this, 'notice_error_unspecified' ) );
			return false;
		}

		add_action( 'admin_notices', array( $this, 'notice_success_convert' ) );

		return true;

	}

	/**
	 * Notice to alert user of unspecified error.
	 *
	 * @since 1.0.0
	 */
	public function notice_error_unspecified() {

		$message = __( 'Something went wrong. Please make your selection and try again.', 'seo-data-transporter' );
		printf( '<div class="notice notice-error"><p>%s</p></div>', $message );

	}

	/**
	 * Notice to alert user of selection error.
	 *
	 * @since 1.0.0
	 */
	public function notice_error_select() {

		$message = __( 'You must choose two different platforms before submitting.', 'seo-data-transporter' );
		printf( '<div class="notice notice-error"><p>%s</p></div>', $message );

	}

	/**
	 * Notice to alert user of a successful analysis.
	 *
	 * @since 1.0.0
	 */
	public function notice_success_analyze() {

		echo '<div class="notice">';

			printf( '<p><b>%s</b></p>', __( 'Compatible Elements:', 'seo-data-transporter' ) );
			echo '<ol>';
			foreach ( (array) $this->analysis_result->elements as $element ) {
				printf( '<li>%s</li>', $element );
			}
			echo '</ol>';

			echo '<p>';
			printf( __( 'The analysis found %d compatible database records to be converted.', 'seo-data-transporter' ), $this->analysis_result->update );
			echo '</p>';

		echo '</div>';

	}

	/**
	 * Notice to alert user of a successful conversion.
	 *
	 * @since 1.0.0
	 */
	public function notice_success_convert() {

		echo '<div class="notice notice-success">';
			printf( '<p><b>%d</b> %s</p>', isset( $this->conversion_result->updated ) ? $this->conversion_result->updated : 0, __( 'records were updated', 'seo-data-transporter' ) );
			printf( '<p><b>%d</b> %s</p>', isset( $this->conversion_result->ignored ) ? $this->conversion_result->ignored : 0, __( 'records were ignored', 'seo-data-transporter' ) );
		echo '</div>';

	}

}
