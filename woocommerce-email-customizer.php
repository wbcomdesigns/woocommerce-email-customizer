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
 * Description:       Email Customizer For WooCommerce plugin allows you to personalize your transactional emails. The plugin allows you to insert various elements into the template, such as text, images, Header, Footer, and much more.
 * Version:           1.2.0
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
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION', '1.2.0' );
}
if ( ! defined( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_DIR' ) ) {
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_DIR', trailingslashit( dirname( __FILE__ ) ) );
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
require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-email-customizer-for-woocommerce-activator.php
 */
function activate_email_customizer_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce-activator.php';
	Email_Customizer_For_Woocommerce_Activator::activate();
}
require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce-radio-image.php';
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

	}
}


if ( ! function_exists( 'wb_email_customizer_admin_notice' ) ) {
	/**
	 * Admin notice if WooCommerce not found.
	 *
	 * @since    1.0.0
	 */
	function wb_email_customizer_admin_notice() {
		$email_customizer_plugin = esc_html__( 'Wbcom Designs – Woocommerce Email Customizer', 'email-customizer-for-woocommerce' );
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
		echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'email-customizer-for-woocommerce' ), '<strong>' . esc_html( $email_customizer_plugin ) . '</strong>', '<strong>' . wp_kses_post( $plugin_install_link ) . '</strong>' );
		echo '</p></div>';
	}
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce.php';

/**
 * Require plugin license file.
 */
require plugin_dir_path( __FILE__ ) . 'edd-license/edd-plugin-license.php';

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

	$plugin = new Email_Customizer_For_Woocommerce();
	$plugin->run();
}
run_email_customizer_for_woocommerce();

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
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( admin_url( 'admin.php?page=wb-email-customizer-settings&tab=wb-email-customizer-welcome' ) );
			exit;
		}
	}

}
