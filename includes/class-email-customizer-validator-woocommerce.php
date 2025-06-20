<?php

class WB_Email_Customizer_Validator {
    
    /**
     * Validate email template selection
     */
    public function validate_template($template): string {
        $allowed_templates = ['default', 'template-one', 'template-two', 'template-three'];
        $template = sanitize_key($template);
        return in_array($template, $allowed_templates, true) ? $template : 'default';
    }
    
    /**
     * Validate color values
     */
    public function validate_color($color): string {
        $color = sanitize_hex_color($color);
        return $color ? $color : '#ffffff';
    }
    
    /**
     * Validate numeric values with range
     */
    public function validate_numeric($value, int $min = 0, int $max = 100): int {
        $value = absint($value);
        return max($min, min($max, $value));
    }
    
    /**
     * Validate text with length limit
     */
    public function validate_text($text, int $max_length = 500): string {
        $text = sanitize_text_field($text);
        return mb_substr($text, 0, $max_length, 'UTF-8');
    }
    
    /**
     * Validate URL
     */
    public function validate_url($url): string {
        return esc_url_raw($url);
    }
    
    /**
     * Validate alignment options
     */
    public function validate_alignment($alignment): string {
        $allowed = ['left', 'center', 'right'];
        return in_array($alignment, $allowed, true) ? $alignment : 'center';
    }
    
    /**
     * Validate border style
     */
    public function validate_border_style_cb($style): string {
        $allowed = ['solid', 'dashed', 'dotted', 'double', 'none'];
        return in_array($style, $allowed, true) ? $style : 'solid';
    }
    
    /**
     * Validate font family
     */
    public function validate_font_family_cb($font): string {
        $allowed = ['sans-serif', 'serif'];
        return in_array($font, $allowed, true) ? $font : 'sans-serif';
    }

    /**
     * Sanitize template choice
     */
    public function sanitize_template_choice($value) {
        $allowed_templates = ['default', 'template-one', 'template-two', 'template-three'];
        return in_array($value, $allowed_templates, true) ? $value : 'default';
    }

    /**
     * Validate template choice
     */
    public function validate_template_choice($validity, $value) {
        $allowed_templates = ['default', 'template-one', 'template-two', 'template-three'];
        if (!in_array($value, $allowed_templates, true)) {
            $validity->add('invalid_template', __('Invalid template selection.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize font size
     */
    public function sanitize_font_size($value) {
        $value = absint($value);
        return ($value >= 8 && $value <= 72) ? $value : 14;
    }

    /**
     * Validate font size
     */
    public function validate_font_size($validity, $value) {
        $value = absint($value);
        if ($value < 8 || $value > 72) {
            $validity->add('invalid_font_size', __('Font size must be between 8 and 72 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize heading text
     */
    public function sanitize_heading_text($value) {
        $value = sanitize_text_field($value);
        return strlen($value) <= 200 ? $value : substr($value, 0, 200);
    }

    /**
     * Validate heading text
     */
    public function validate_heading_text($validity, $value) {
        if (strlen($value) > 200) {
            $validity->add('text_too_long', __('Heading text is too long. Maximum 200 characters allowed.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize subheading text
     */
    public function sanitize_subheading_text($value) {
        $value = sanitize_text_field($value);
        return strlen($value) <= 300 ? $value : substr($value, 0, 300);
    }

    /**
     * Validate subheading text
     */
    public function validate_subheading_text($validity, $value) {
        if (strlen($value) > 300) {
            $validity->add('text_too_long', __('Subheading text is too long. Maximum 300 characters allowed.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize body text
     */
    public function sanitize_body_text($value) {
        $value = wp_kses_post($value);
        return strlen($value) <= 1000 ? $value : substr($value, 0, 1000);
    }

    /**
     * Validate body text
     */
    public function validate_body_text($validity, $value) {
        if (strlen($value) > 1000) {
            $validity->add('text_too_long', __('Body text is too long. Maximum 1000 characters allowed.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize footer text
     */
    public function sanitize_footer_text($value) {
        $value = wp_kses_post($value);
        return strlen($value) <= 500 ? $value : substr($value, 0, 500);
    }

    /**
     * Validate footer text
     */
    public function validate_footer_text($validity, $value) {
        if (strlen($value) > 500) {
            $validity->add('text_too_long', __('Footer text is too long. Maximum 500 characters allowed.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Validate color value
     */
    public function validate_color_value($validity, $value) {
        if (!sanitize_hex_color($value)) {
            $validity->add('invalid_color', __('Please enter a valid hex color code.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize padding value
     */
    public function sanitize_padding_value($value) {
        $value = absint($value);
        return ($value >= 0 && $value <= 100) ? $value : 0;
    }

    /**
     * Validate padding value
     */
    public function validate_padding_value($validity, $value) {
        $value = absint($value);
        if ($value < 0 || $value > 100) {
            $validity->add('invalid_padding', __('Padding must be between 0 and 100 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize border value
     */
    public function sanitize_border_value($value) {
        $value = absint($value);
        return ($value >= 0 && $value <= 20) ? $value : 0;
    }

    /**
     * Validate border value
     */
    public function validate_border_value($validity, $value) {
        $value = absint($value);
        if ($value < 0 || $value > 20) {
            $validity->add('invalid_border', __('Border width must be between 0 and 20 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize border radius
     */
    public function sanitize_border_radius($value) {
        $value = absint($value);
        return ($value >= 0 && $value <= 50) ? $value : 0;
    }

    /**
     * Validate border radius
     */
    public function validate_border_radius($validity, $value) {
        $value = absint($value);
        if ($value < 0 || $value > 50) {
            $validity->add('invalid_border_radius', __('Border radius must be between 0 and 50 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize shadow value
     */
    public function sanitize_shadow_value($value) {
        $value = absint($value);
        return ($value >= 0 && $value <= 30) ? $value : 0;
    }

    /**
     * Validate shadow value
     */
    public function validate_shadow_value($validity, $value) {
        $value = absint($value);
        if ($value < 0 || $value > 30) {
            $validity->add('invalid_shadow', __('Shadow spread must be between 0 and 30 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize font family
     */
    public function sanitize_font_family($value) {
        $allowed_fonts = [
            'Arial, sans-serif',
            'Helvetica, sans-serif',
            'Georgia, serif',
            'Times New Roman, serif',
            'Courier New, monospace',
            'Verdana, sans-serif',
            'Trebuchet MS, sans-serif'
        ];
        return in_array($value, $allowed_fonts, true) ? $value : 'Arial, sans-serif';
    }

    /**
     * Validate font family
     */
    public function validate_font_family($validity, $value) {
        $allowed_fonts = [
            'Arial, sans-serif',
            'Helvetica, sans-serif',
            'Georgia, serif',
            'Times New Roman, serif',
            'Courier New, monospace',
            'Verdana, sans-serif',
            'Trebuchet MS, sans-serif'
        ];
        if (!in_array($value, $allowed_fonts, true)) {
            $validity->add('invalid_font', __('Please select a valid font family.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize placement choice
     */
    public function sanitize_placement_choice($value) {
        $allowed_placements = ['above', 'below', 'none'];
        return in_array($value, $allowed_placements, true) ? $value : 'above';
    }

    /**
     * Validate placement choice
     */
    public function validate_placement_choice($validity, $value) {
        $allowed_placements = ['above', 'below', 'none'];
        if (!in_array($value, $allowed_placements, true)) {
            $validity->add('invalid_placement', __('Invalid image placement selection.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize alignment choice
     */
    public function sanitize_alignment_choice($value) {
        $allowed_alignments = ['left', 'center', 'right'];
        return in_array($value, $allowed_alignments, true) ? $value : 'center';
    }

    /**
     * Validate alignment choice
     */
    public function validate_alignment_choice($validity, $value) {
        $allowed_alignments = ['left', 'center', 'right'];
        if (!in_array($value, $allowed_alignments, true)) {
            $validity->add('invalid_alignment', __('Invalid alignment selection.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Validate image URL
     */
    public function validate_image_url($validity, $value) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $validity->add('invalid_url', __('Please enter a valid image URL.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize width value
     */
    public function sanitize_width_value($value) {
        $value = absint($value);
        return ($value >= 300 && $value <= 800) ? $value : 600;
    }

    /**
     * Validate width value
     */
    public function validate_width_value($validity, $value) {
        $value = absint($value);
        if ($value < 300 || $value > 800) {
            $validity->add('invalid_width', __('Email width must be between 300 and 800 pixels.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }

    /**
     * Sanitize border style
     */
    public function sanitize_border_style($value) {
        $allowed_styles = ['solid', 'dotted', 'dashed', 'double', 'none'];
        return in_array($value, $allowed_styles, true) ? $value : 'solid';
    }

    /**
     * Validate border style
     */
    public function validate_border_style($validity, $value) {
        $allowed_styles = ['solid', 'dotted', 'dashed', 'double', 'none'];
        if (!in_array($value, $allowed_styles, true)) {
            $validity->add('invalid_border_style', __('Invalid border style selection.', 'email-customizer-for-woocommerce'));
        }
        return $validity;
    }
}