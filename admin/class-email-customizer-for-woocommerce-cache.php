<?php
/**
 * Class for caching options and email styles in WooCommerce Email Customizer
 * with improved transient management
 */
class WB_Email_Customizer_Cache {
    private static $option_cache = array();
    
    const MASTER_TRANSIENT_KEY = 'wc_email_customizer_transient_keys';
    const TRANSIENT_PREFIX = 'wc_email_styles_';
    
    /**
     * Get cached option or retrieve and cache it
     *
     * @param string $option_name
     * @param mixed $default
     * @return mixed
     */
    public static function get_option($option_name, $default = false) {
        if (!isset(self::$option_cache[$option_name])) {
            self::$option_cache[$option_name] = get_option($option_name, $default);
        }
        return self::$option_cache[$option_name];
    }
    
    /**
     * Get cached email styles
     *
     * @param string $cache_key
     * @return mixed|false
     */
    public static function get_email_styles($cache_key) {
        $transient_name = self::TRANSIENT_PREFIX . $cache_key;
        return get_transient($transient_name);
    }
    
    /**
     * Set cached email styles and track the transient key
     *
     * @param string $cache_key
     * @param mixed $styles
     * @param int $expiration 
     * @return bool
     */
    public static function set_email_styles($cache_key, $styles, $expiration = 3600) {
        $transient_name = self::TRANSIENT_PREFIX . $cache_key;
        
        // Store the actual styles data
        if (!set_transient($transient_name, $styles, $expiration)) {
            error_log('Failed to set email styles transient: ' . $transient_name);
            return false;
        }
        
        // Track this transient in our master list
        $tracked_keys = get_transient(self::MASTER_TRANSIENT_KEY);
        if (!is_array($tracked_keys)) {
            $tracked_keys = array();
        }
        
        if (!in_array($transient_name, $tracked_keys)) {
            $tracked_keys[] = $transient_name;
            // Set longer expiration for the master list
            if (!set_transient(self::MASTER_TRANSIENT_KEY, $tracked_keys, $expiration + 86400)) {
                error_log('Failed to update master transient keys list');
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Clear all cached data including options and transients
     */
    public static function clear_cache() {
        // Clear option cache
        self::$option_cache = array();
        
        // Get all tracked transient keys
        $tracked_keys = get_transient(self::MASTER_TRANSIENT_KEY);
        
        if (is_array($tracked_keys)) {
            foreach ($tracked_keys as $transient_name) {
                if (!delete_transient($transient_name)) {
                    error_log('Failed to delete transient: ' . $transient_name);
                }
            }
        }
        
        // Clear the master tracking transient
        if (!delete_transient(self::MASTER_TRANSIENT_KEY)) {
            error_log('Failed to delete master transient keys list');
        }
    }
    
    /**
     * Get list of all active style transient keys
     *
     * @return array
     */
    public static function get_active_transient_keys() {
        $keys = get_transient(self::MASTER_TRANSIENT_KEY);
        return is_array($keys) ? $keys : array();
    }
    
    /**
     * Delete specific style transient by key
     *
     * @param string $cache_key
     * @return bool
     */
    public static function delete_email_styles($cache_key) {
        $transient_name = self::TRANSIENT_PREFIX . $cache_key;
        $success = delete_transient($transient_name);
        
        if ($success) {
            // Update the master key list
            $keys = get_transient(self::MASTER_TRANSIENT_KEY);
            if (is_array($keys) && ($index = array_search($transient_name, $keys)) !== false) {
                unset($keys[$index]);
                set_transient(self::MASTER_TRANSIENT_KEY, $keys, 86400);
            }
        }
        
        return $success;
    }
}
