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

		if ( ! get_option( 'woocommerce_email_template' ) ) {
			update_option( 'woocommerce_email_template', 'default' );
			update_option( 'woocommerce_email_header_text_color', '#ffffff', true );
			update_option( 'woocommerce_email_body_background_color', '#fdfdfd', true );
			update_option( 'woocommerce_email_header_background_color', '#557da1', true );
			update_option( 'woocommerce_email_footer_address_background_color', '#ffffff', true );
			update_option( 'woocommerce_email_footer_address_border', '1', true );
			update_option( 'woocommerce_email_rounded_corners', '6', true );
			update_option( 'woocommerce_email_border_container_top', '1', true );
			update_option( 'woocommerce_email_border_container_bottom', '1', true );
			update_option( 'woocommerce_email_border_container_left', '1', true );
			update_option( 'woocommerce_email_border_container_right', '1', true );
			update_option( 'woocommerce_email_body_border_color', '#505050', true );
			update_option( 'woocommerce_email_footer_text_color', '#ffffff', true );
			update_option( 'woocommerce_email_footer_background_color', '#202020', true );
		}

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

		$default_array = get_option( 'wb_email_customizer_old' );
		if ( ! empty( $default_array ) ) {
			foreach ( $default_array as $key => $value ) {
				update_option( $key, $value, true );
			}
			delete_option( 'wb_email_customizer_old' );
		}
	}
}
