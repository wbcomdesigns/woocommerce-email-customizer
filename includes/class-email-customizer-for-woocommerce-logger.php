<?php
// Add error handling and logging class
class WB_Email_Customizer_Logger {
    
    /**
     * Log error with context
     */
    public static function log_error($message, $context = array()) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $log_message = sprintf(
                '[WC Email Customizer] %s - Context: %s',
                $message,
                wp_json_encode($context)
            );
            error_log($log_message);
        }
    }
    
    /**
     * Log info for debugging
     */
    public static function log_info($message, $context = array()) {
        if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            $log_message = sprintf(
                '[WC Email Customizer INFO] %s - Context: %s',
                $message,
                wp_json_encode($context)
            );
            error_log($log_message);
        }
    }
}
