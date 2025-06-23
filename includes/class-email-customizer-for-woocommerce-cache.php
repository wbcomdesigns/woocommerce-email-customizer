<?php
/**
 * Enhanced caching class for WooCommerce Email Customizer
 * with persistent tracking of all cached options and transients
 */
class WB_Email_Customizer_Cache {
    const MASTER_OPTION_KEY = 'wb_email_customizer_cached_options';
    const MASTER_TRANSIENT_KEY = 'wc_email_customizer_transient_keys';
    const TRANSIENT_PREFIX = 'wc_email_styles_';
    
    private static $option_cache = array();
    
    /**
     * Get option with caching
     *
     * @param string $option_name
     * @param mixed $default
     * @return mixed
     */
    public static function get_option($option_name, $default = false) {
        // Check memory cache first
       
        if (isset(self::$option_cache[$option_name])) {
            return self::$option_cache[$option_name];
        }
        
        // Get from WordPress options
        //   var_dump($option_name);
        // var_dump($default);
        $value = get_option($option_name, $default);
         
            // var_dump($value);
            // die();
        // Store in memory cache
        self::$option_cache[$option_name] = $value;
        
        // Track this option in persistent storage
        $tracked_options = get_option(self::MASTER_OPTION_KEY, array());
        if (!in_array($option_name, $tracked_options)) {
            $tracked_options[] = $option_name;
            update_option(self::MASTER_OPTION_KEY, $tracked_options, false);
        }
      
        return $value;
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
            if (!set_transient(self::MASTER_TRANSIENT_KEY, $tracked_keys, $expiration + 86400)) {
                error_log('Failed to update master transient keys list');
            }
        }
        
        return true;
    }
    
    /**
     * Clear all cached data (options and transients)
     *
     * @return array Summary of cleared items
     */
    public static function clear_cache() {
        $result = array(
            'options' => 0,
            'transients' => 0
        );
        
        // Clear in-memory option cache
        self::$option_cache = array();
        
        // Clear tracked persistent options
        $tracked_options = get_option(self::MASTER_OPTION_KEY, array());
        if (!empty($tracked_options)) {
            $result['options'] = count($tracked_options);
            foreach ($tracked_options as $option_name) {
                delete_option($option_name);
            }
            delete_option(self::MASTER_OPTION_KEY);
        }
        
        // Clear tracked transients
        $tracked_keys = get_transient(self::MASTER_TRANSIENT_KEY);
        if (is_array($tracked_keys)) {
            $result['transients'] = count($tracked_keys);
            foreach ($tracked_keys as $transient_name) {
                delete_transient($transient_name);
            }
            delete_transient(self::MASTER_TRANSIENT_KEY);
        }
        
        return $result;
    }
    
    /**
     * Get list of all cached option names
     *
     * @return array
     */
    public static function get_cached_options() {
        return get_option(self::MASTER_OPTION_KEY, array());
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
     * Delete specific cached option
     *
     * @param string $option_name
     * @return bool
     */
    public static function delete_cached_option($option_name) {
        // Remove from memory cache
        unset(self::$option_cache[$option_name]);
        
        // Remove from persistent storage
        $tracked_options = get_option(self::MASTER_OPTION_KEY, array());
        if (($key = array_search($option_name, $tracked_options)) !== false) {
            unset($tracked_options[$key]);
            update_option(self::MASTER_OPTION_KEY, $tracked_options, false);
        }
        
        // Delete the actual option
        return delete_option($option_name);
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
