<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Email_Customizer_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Email_Customizer_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'email-customizer-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_subscription_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Email_Customizer_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Email_Customizer_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Email_Customizer_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Email_Customizer_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-email-customizer-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-email-customizer-for-woocommerce-i18n.php';

		/**
		 * The class responsible for validating the settings
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-email-customizer-validator-woocommerce.php';
		
		/**
		 * The class responsible for handling settings with caching
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-email-customizer-for-woocommerce-cache.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-email-customizer-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-email-customizer-for-woocommerce-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

		/* Enqueue wbcom license file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-paid-plugin-settings.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-subscription-handler.php';
		
		$this->loader = new Email_Customizer_For_Woocommerce_Loader();

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/emails/class-custom-wc-emai-template.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Email_Customizer_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Email_Customizer_For_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Email_Customizer_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_ajax_woocommerce_email_customizer_send_email', $plugin_admin, 'wb_email_customizer_send_email' );
		$this->loader->add_action( 'customize_controls_enqueue_scripts', $plugin_admin, 'wb_email_customizer_enqueue_customizer_control_script' );
		$this->loader->add_filter( 'woocommerce_email_footer_text', $plugin_admin, 'wb_email_customizer_email_footer_text' );
		$this->loader->add_filter( 'woocommerce_email_styles', $plugin_admin, 'wb_email_customizer_add_styles' );
		$this->loader->add_filter( 'query_vars', $plugin_admin, 'wb_email_customizer_add_query_vars' );
		$this->loader->add_action( 'customize_preview_init', $plugin_admin, 'wb_email_customizer_enqueue_customizer_script' );
		$this->loader->add_action( 'template_redirect', $plugin_admin, 'wb_email_customizer_load_email_template', 10);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'customize_register', $plugin_admin, 'wb_email_customizer_add_sections', 40 );
		$this->loader->add_filter( 'customize_register', $plugin_admin, 'wb_email_customizer_add_controls', 50 );
		$this->loader->add_filter( 'customize_register', $plugin_admin, 'wb_email_customizer_add_customizer_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wb_email_customizer_admin_setting_submenu_pages' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wb_email_customizer_init_plugin_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wb_email_customizer_views_add_admin_settings' );
		$this->loader->add_action( 'in_admin_header', $plugin_admin, 'wb_email_hide_all_admin_notices_from_setting_page');

		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_admin, 'wb_email_customizer_custom_universal_email_template_override',20,3 );

		$this->loader->add_filter( 'customize_save_after', $plugin_admin, 'wb_email_customizer_load_template_presets_cb',10);
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Email_Customizer_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}


	/**
	 * Register all of the hooks related to the subscription plugin functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_subscription_hooks() {

		$plugin_subscription = new Email_Customizer_Subscription_Handler( $this->get_plugin_name(), $this->get_version() );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Email_Customizer_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
