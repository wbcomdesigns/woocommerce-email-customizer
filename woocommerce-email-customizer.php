<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Email_Customizer_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs – Woocommerce Email Customizer
 * Plugin URI:        https://wbcomdesigns.com/downloads/email-customizer-for-woocommerce
 * Description:       The Email Customizer For WooCommerce plugin allows you to personalize your transactional emails. You can insert various elements into the template, such as text, images, headers, footers, and much more.
 * Version:           1.3.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       email-customizer-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION', '1.3.0' );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_DIR' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_DIR', trailingslashit( __DIR__ ) );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_BASENAME' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_FILE' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__ );
}
if ( is_customize_preview() ) {
	require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-email-customizer-for-woocommerce-activator.php
 */
function activate_email_customizer_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce-activator.php';
	Email_Customizer_For_Woocommerce_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-email-customizer-for-woocommerce-deactivator.php
 */
function deactivate_email_customizer_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce-deactivator.php';
	Email_Customizer_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_email_customizer_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_email_customizer_for_woocommerce' );

if ( ! function_exists( 'wb_email_customizer_check_woocomerce' ) ) {

	add_action( 'admin_init', 'wb_email_customizer_check_woocomerce' );
	/**
	 * Function check for woocommerce is installed and activate.
	 *
	 * @since    1.0.0
	 */
	function wb_email_customizer_check_woocomerce() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_action( 'admin_notices', 'wb_email_customizer_admin_notice' );
		} else {
			run_email_customizer_for_woocommerce();
		}
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}
}


if ( ! function_exists( 'wb_email_customizer_admin_notice' ) ) {
	/**
	 * Admin notice if WooCommerce not found.
	 *
	 * @since    1.0.0
	 */
	function wb_email_customizer_admin_notice() {
		$email_customizer_plugin = esc_html__( 'Wbcom Designs – WooCommerce Email Customizer', 'email-customizer-for-woocommerce' );
		$woo_plugin              = esc_html__( 'WooCommerce', 'email-customizer-for-woocommerce' );
		$action                  = 'install-plugin';
		$slug                    = 'woocommerce';
		$plugin_install_link     = '<a href="' . wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug,
				),
				admin_url( 'update.php' )
			),
			$action . '_' . $slug
		) . '">' . $woo_plugin . '</a>';
		echo '<div class="error"><p>';
		/* Translators: %1$s: Woo Product Inquiry & Quote Pro, %2$s: WooCommerce   */
		printf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'email-customizer-for-woocommerce' ), '<strong>' . esc_html( $email_customizer_plugin ) . '</strong>', '<strong>' . wp_kses_post( $plugin_install_link ) . '</strong>' );
		echo '</p></div>';
	}
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce.php';

// EDD Software Licensing SDK.
add_action(
	'edd_sl_sdk_registry',
	function ( $registry ) {
		$registry->register(
			array(
				'id'      => 'woocommerce-email-customizer',
				'url'     => 'https://wbcomdesigns.com',
				'item_id' => 11, // TODO: Replace with actual EDD download ID from wbcomdesigns.com.
				'version' => EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION,
				'file'    => EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_FILE,
			)
		);
	}
);
if ( file_exists( __DIR__ . '/vendor/edd-sl-sdk/edd-sl-sdk.php' ) ) {
	require_once __DIR__ . '/vendor/edd-sl-sdk/edd-sl-sdk.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_email_customizer_for_woocommerce() {
	// Prevent double initialization.
	static $initialized = false;
	if ( $initialized ) {
		return;
	}
	$initialized = true;

	$plugin = new Email_Customizer_For_Woocommerce();
	$plugin->run();
}
add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( 'WooCommerce' ) ) {
			run_email_customizer_for_woocommerce();
		}
	}
);

// Declare HPOS compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

add_action( 'activated_plugin', 'wb_email_customizer_activation_redirect_settings' );

/**
 * Redirect to plugin settings page after activated.
 *
 * @since  1.0.0
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function wb_email_customizer_activation_redirect_settings( $plugin ) {
	if ( plugin_basename( __FILE__ ) === $plugin && class_exists( 'WooCommerce' ) ) {
		if ( isset( $_REQUEST['action'] ) && 'activate' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) && isset( $_REQUEST['plugin'] ) && $plugin === sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( admin_url( 'admin.php?page=wb-email-customizer-settings&tab=wb-email-customizer-welcome' ) );
			exit;
		}
	}
}

/**
 * Add settings link to plugin action links.
 *
 * @param array $links Plugin action links.
 * @return array Modified plugin action links.
 */
function your_plugin_add_settings_link( $links ) {
	$url = admin_url( 'customize.php' );
	// Add the preview URL to the customizer.
	$nonce = wp_create_nonce( 'preview-mail' );
	// Generate the email preview URL (not the customizer URL).
	$preview_url = home_url( '/' );
	$preview_url = add_query_arg( 'email-customizer-for-woocommerce', 'true', $preview_url );
	$preview_url = add_query_arg( '_wpnonce', $nonce, $preview_url );

	$url = add_query_arg( 'url', rawurlencode( $preview_url ), $url );
	// Add our identifier to know we're in email customizer mode.
	$url           = add_query_arg( 'email-customizer-for-woocommerce', 'true', $url );
	$settings_link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'email-customizer-for-woocommerce' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'your_plugin_add_settings_link' );
