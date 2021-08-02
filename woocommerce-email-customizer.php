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
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
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
	define( 'EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
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

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-email-customizer-for-woocommerce-activator.php
 */
function activate_email_customizer_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce-activator.php';
	Email_Customizer_For_Woocommerce_Activator::activate();
	do_action( 'wb_email_customizer_update_option' );
}
/**
 * Activation tasks.
 */
function wb_email_customizer_update_option() {
	// Save current email settings.
	$header_image     = get_option( 'woocommerce_email_header_image', 'http://' );
	$footer_text      = get_option( 'woocommerce_email_footer_text', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' - Powered by WooCommerce' );
	$base_color       = get_option( 'woocommerce_email_base_color', '#557da1' );
	$background_color = get_option( 'woocommerce_email_background_color', '#f5f5f5' );
	$body_bg_color    = get_option( 'woocommerce_email_body_background_color', '#fdfdfd' );
	$text_color       = get_option( 'woocommerce_email_text_color', '#505050' );

	$settings = array(
		'woocommerce_email_header_image'          => $header_image,
		'woocommerce_email_footer_text'           => $footer_text,
		'woocommerce_email_base_color'            => $base_color,
		'woocommerce_email_background_color'      => $background_color,
		'woocommerce_email_body_background_color' => $body_bg_color,
		'woocommerce_email_text_color'            => $text_color,
	);

	update_option( 'wc_email_customizer_old_settings', $settings );

	return true;
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
			unset( $_GET['activate'] );
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
		echo '<div class="error"><p>';
		/* Translators: %1$s: Woo Product Inquiry & Quote Pro, %2$s: WooCommerce   */
		echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'email-customizer-for-woocommerce' ), '<strong>' . esc_html( $email_customizer_plugin ) . '</strong>', '<strong>' . esc_html( $woo_plugin ) . '</strong>' );
		echo '</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

	}
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer-for-woocommerce.php';

require plugin_dir_path( __FILE__ ) . 'wec-update-checker\wec-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://demos.wbcomdesigns.com/exporter/free-plugins/woocommerce-email-customizer.json',
	__FILE__, // Full path to the main plugin file or functions.php.
	'woocommerce-email-customizer'
);

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
