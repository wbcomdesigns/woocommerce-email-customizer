<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Email_Customizer_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

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
	}

}
