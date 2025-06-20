<?php

class WB_Email_Customizer_Validator {
    
    /**
     * Validate email template selection
     */
    public static function validate_template($template): string {
        $allowed_templates = ['default', 'template-one', 'template-two', 'template-three'];
        $template = sanitize_key($template);
        return in_array($template, $allowed_templates, true) ? $template : 'default';
    }
    
    /**
     * Validate color values
     */
    public static function validate_color($color): string {
        $color = sanitize_hex_color($color);
        return $color ? $color : '#ffffff';
    }
    
    /**
     * Validate numeric values with range
     */
    public static function validate_numeric($value, int $min = 0, int $max = 100): int {
        $value = absint($value);
        return max($min, min($max, $value));
    }
    
    /**
     * Validate text with length limit
     */
    public static function validate_text($text, int $max_length = 500): string {
        $text = sanitize_text_field($text);
        return mb_substr($text, 0, $max_length, 'UTF-8');
    }
    
    /**
     * Validate URL
     */
    public static function validate_url($url): string {
        return esc_url_raw($url);
    }
    
    /**
     * Validate alignment options
     */
    public static function validate_alignment($alignment): string {
        $allowed = ['left', 'center', 'right'];
        return in_array($alignment, $allowed, true) ? $alignment : 'center';
    }
    
    /**
     * Validate border style
     */
    public static function validate_border_style($style): string {
        $allowed = ['solid', 'dashed', 'dotted', 'double', 'none'];
        return in_array($style, $allowed, true) ? $style : 'solid';
    }
    
    /**
     * Validate font family
     */
    public static function validate_font_family($font): string {
        $allowed = ['sans-serif', 'serif'];
        return in_array($font, $allowed, true) ? $font : 'sans-serif';
    }
}