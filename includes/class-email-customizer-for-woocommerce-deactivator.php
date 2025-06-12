<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Email_Customizer_For_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
			update_option( 'woocommerce_email_header_text_color', '#ffffff',true ) ;
			update_option( 'woocommerce_email_body_background_color', '#fdfdfd',true ) ;
			update_option( 'woocommerce_email_header_background_color', '#557da1',true ) ;
			update_option( 'woocommerce_email_footer_address_background_color', '#202020',true ) ;
			update_option( 'woocommerce_email_footer_address_border', '12',true ) ;
			update_option( 'woocommerce_email_rounded_corners', '6',true ) ;
			update_option( 'woocommerce_email_border_container_top', '1',true ) ;
			update_option( 'woocommerce_email_border_container_bottom','1',true ) ;
			update_option( 'woocommerce_email_border_container_left', '1',true ) ;
			update_option( 'woocommerce_email_border_container_right', '1',true ) ;
			update_option( 'woocommerce_email_body_border_color', '#505050',true ) ;
			update_option( 'woocommerce_email_footer_text_color', '#ffffff',true ) ;
			update_option( 'woocommerce_email_footer_background_color', '#202020',true ) ;	
	}

}
