<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Error handling and logging class for WooCommerce Email Customizer.
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 */

/**
 * Class WB_Email_Customizer_Logger
 *
 * Provides logging functionality for the email customizer plugin.
 *
 * @since 1.0.0
 */
class WB_Email_Customizer_Logger {

	/**
	 * Log error with context.
	 *
	 * @param string $message The error message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public static function log_error( $message, $context = array() ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$log_message = sprintf(
				'[WC Email Customizer] %s - Context: %s',
				$message,
				wp_json_encode( $context )
			);
			error_log( $log_message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	/**
	 * Log info for debugging.
	 *
	 * @param string $message The info message.
	 * @param array  $context Additional context data.
	 * @return void
	 */
	public static function log_info( $message, $context = array() ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			$log_message = sprintf(
				'[WC Email Customizer INFO] %s - Context: %s',
				$message,
				wp_json_encode( $context )
			);
			error_log( $log_message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}
}
