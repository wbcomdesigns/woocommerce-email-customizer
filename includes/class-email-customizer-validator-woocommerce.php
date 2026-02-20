<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Validator class for WooCommerce Email Customizer.
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/includes
 */

/**
 * Class WB_Email_Customizer_Validator
 *
 * Handles validation and sanitization of email customizer settings.
 *
 * @since 1.0.0
 */
class WB_Email_Customizer_Validator {

	/**
	 * Validate email template selection.
	 *
	 * @param string $template The template to validate.
	 * @return string Validated template name.
	 */
	public function validate_template( $template ): string {
		$allowed_templates = array( 'default', 'template-one', 'template-two', 'template-three' );
		$template          = sanitize_key( $template );
		return in_array( $template, $allowed_templates, true ) ? $template : 'default';
	}

	/**
	 * Validate color values.
	 *
	 * @param string $color The hex color value to validate.
	 * @return string Validated hex color.
	 */
	public function validate_color( $color ): string {
		$color = sanitize_hex_color( $color );
		return $color ? $color : '#ffffff';
	}

	/**
	 * Validate numeric values with range.
	 *
	 * @param mixed $value The value to validate.
	 * @param int   $min   Minimum allowed value.
	 * @param int   $max   Maximum allowed value.
	 * @return int Validated numeric value.
	 */
	public function validate_numeric( $value, int $min = 0, int $max = 100 ): int {
		$value = absint( $value );
		return max( $min, min( $max, $value ) );
	}

	/**
	 * Validate text with length limit.
	 *
	 * @param string $text       The text to validate.
	 * @param int    $max_length Maximum allowed length.
	 * @return string Validated text.
	 */
	public function validate_text( $text, int $max_length = 500 ): string {
		$text = sanitize_text_field( $text );
		return mb_substr( $text, 0, $max_length, 'UTF-8' );
	}

	/**
	 * Validate URL.
	 *
	 * @param string $url The URL to validate.
	 * @return string Validated URL.
	 */
	public function validate_url( $url ): string {
		return esc_url_raw( $url );
	}

	/**
	 * Validate alignment options.
	 *
	 * @param string $alignment The alignment value to validate.
	 * @return string Validated alignment.
	 */
	public function validate_alignment( $alignment ): string {
		$allowed = array( 'left', 'center', 'right' );
		return in_array( $alignment, $allowed, true ) ? $alignment : 'center';
	}

	/**
	 * Validate border style.
	 *
	 * @param string $style The border style to validate.
	 * @return string Validated border style.
	 */
	public function validate_border_style_cb( $style ): string {
		$allowed = array( 'solid', 'dashed', 'dotted', 'double', 'none' );
		return in_array( $style, $allowed, true ) ? $style : 'solid';
	}

	/**
	 * Validate font family.
	 *
	 * @param string $font The font family to validate.
	 * @return string Validated font family.
	 */
	public function validate_font_family_cb( $font ): string {
		$allowed = array( 'sans-serif', 'serif' );
		return in_array( $font, $allowed, true ) ? $font : 'sans-serif';
	}

	/**
	 * Sanitize template choice.
	 *
	 * @param string $value The template value to sanitize.
	 * @return string Sanitized template choice.
	 */
	public function sanitize_template_choice( $value ) {
		$allowed_templates = array( 'default', 'template-one', 'template-two', 'template-three' );
		return in_array( $value, $allowed_templates, true ) ? $value : 'default';
	}

	/**
	 * Validate template choice.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_template_choice( $validity, $value ) {
		$allowed_templates = array( 'default', 'template-one', 'template-two', 'template-three' );
		if ( ! in_array( $value, $allowed_templates, true ) ) {
			$validity->add( 'invalid_template', __( 'Invalid template selection.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize font size.
	 *
	 * @param mixed $value The font size value to sanitize.
	 * @return int Sanitized font size.
	 */
	public function sanitize_font_size( $value ) {
		$value = absint( $value );
		return ( $value >= 8 && $value <= 72 ) ? $value : 14;
	}

	/**
	 * Validate font size.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_font_size( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 8 || $value > 72 ) {
			$validity->add( 'invalid_font_size', __( 'Font size must be between 8 and 72 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize heading text.
	 *
	 * @param string $value The heading text to sanitize.
	 * @return string Sanitized heading text.
	 */
	public function sanitize_heading_text( $value ) {
		$value = sanitize_text_field( $value );
		return strlen( $value ) <= 200 ? $value : substr( $value, 0, 200 );
	}

	/**
	 * Validate heading text.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_heading_text( $validity, $value ) {
		if ( strlen( $value ) > 200 ) {
			$validity->add( 'text_too_long', __( 'Heading text is too long. Maximum 200 characters allowed.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize subheading text.
	 *
	 * @param string $value The subheading text to sanitize.
	 * @return string Sanitized subheading text.
	 */
	public function sanitize_subheading_text( $value ) {
		$value = sanitize_text_field( $value );
		return strlen( $value ) <= 300 ? $value : substr( $value, 0, 300 );
	}

	/**
	 * Validate subheading text.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_subheading_text( $validity, $value ) {
		if ( strlen( $value ) > 300 ) {
			$validity->add( 'text_too_long', __( 'Subheading text is too long. Maximum 300 characters allowed.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize body text.
	 *
	 * @param string $value The body text to sanitize.
	 * @return string Sanitized body text.
	 */
	public function sanitize_body_text( $value ) {
		$value = wp_kses_post( $value );
		return strlen( $value ) <= 1000 ? $value : substr( $value, 0, 1000 );
	}

	/**
	 * Validate body text.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_body_text( $validity, $value ) {
		if ( strlen( $value ) > 1000 ) {
			$validity->add( 'text_too_long', __( 'Body text is too long. Maximum 1000 characters allowed.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize footer text.
	 *
	 * @param string $value The footer text to sanitize.
	 * @return string Sanitized footer text.
	 */
	public function sanitize_footer_text( $value ) {
		$value = wp_kses_post( $value );
		return strlen( $value ) <= 500 ? $value : substr( $value, 0, 500 );
	}

	/**
	 * Validate footer text.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_footer_text( $validity, $value ) {
		if ( strlen( $value ) > 500 ) {
			$validity->add( 'text_too_long', __( 'Footer text is too long. Maximum 500 characters allowed.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Validate color value.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_color_value( $validity, $value ) {
		if ( ! sanitize_hex_color( $value ) ) {
			$validity->add( 'invalid_color', __( 'Please enter a valid hex color code.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize padding value.
	 *
	 * @param mixed $value The padding value to sanitize.
	 * @return int Sanitized padding value.
	 */
	public function sanitize_padding_value( $value ) {
		$value = absint( $value );
		return ( $value >= 0 && $value <= 100 ) ? $value : 0;
	}

	/**
	 * Validate padding value.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_padding_value( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 100 ) {
			$validity->add( 'invalid_padding', __( 'Padding must be between 0 and 100 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize border value.
	 *
	 * @param mixed $value The border value to sanitize.
	 * @return int Sanitized border value.
	 */
	public function sanitize_border_value( $value ) {
		$value = absint( $value );
		return ( $value >= 0 && $value <= 20 ) ? $value : 0;
	}

	/**
	 * Validate border value.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_border_value( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 20 ) {
			$validity->add( 'invalid_border', __( 'Border width must be between 0 and 20 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize border radius.
	 *
	 * @param mixed $value The border radius value to sanitize.
	 * @return int Sanitized border radius.
	 */
	public function sanitize_border_radius( $value ) {
		$value = absint( $value );
		return ( $value >= 0 && $value <= 50 ) ? $value : 0;
	}

	/**
	 * Validate border radius.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_border_radius( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 50 ) {
			$validity->add( 'invalid_border_radius', __( 'Border radius must be between 0 and 50 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize shadow value.
	 *
	 * @param mixed $value The shadow value to sanitize.
	 * @return int Sanitized shadow value.
	 */
	public function sanitize_shadow_value( $value ) {
		$value = absint( $value );
		return ( $value >= 0 && $value <= 30 ) ? $value : 0;
	}

	/**
	 * Validate shadow value.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_shadow_value( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 0 || $value > 30 ) {
			$validity->add( 'invalid_shadow', __( 'Shadow spread must be between 0 and 30 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize font family.
	 *
	 * @param string $value The font family value to sanitize.
	 * @return string Sanitized font family.
	 */
	public function sanitize_font_family( $value ) {
		$allowed_fonts = array( 'sans-serif', 'serif' );
		return in_array( $value, $allowed_fonts, true ) ? $value : 'Arial, sans-serif';
	}

	/**
	 * Validate font family.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_font_family( $validity, $value ) {
		$allowed_fonts = array( 'sans-serif', 'serif' );
		if ( ! in_array( $value, $allowed_fonts, true ) ) {
			$validity->add( 'invalid_font', __( 'Please select a valid font family.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize placement choice.
	 *
	 * @param string $value The placement value to sanitize.
	 * @return string Sanitized placement choice.
	 */
	public function sanitize_placement_choice( $value ) {
		$allowed_placements = array( 'inside', 'outside', '' );
		return in_array( $value, $allowed_placements, true ) ? $value : 'above';
	}

	/**
	 * Validate placement choice.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_placement_choice( $validity, $value ) {
		$allowed_placements = array( 'inside', 'outside', '' );
		if ( ! in_array( $value, $allowed_placements, true ) ) {
			$validity->add( 'invalid_placement', __( 'Invalid image placement selection.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize alignment choice.
	 *
	 * @param string $value The alignment value to sanitize.
	 * @return string Sanitized alignment choice.
	 */
	public function sanitize_alignment_choice( $value ) {
		$allowed_alignments = array( 'left', 'center', 'right' );
		return in_array( $value, $allowed_alignments, true ) ? $value : 'center';
	}

	/**
	 * Validate alignment choice.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_alignment_choice( $validity, $value ) {
		$allowed_alignments = array( 'left', 'center', 'right' );
		if ( ! in_array( $value, $allowed_alignments, true ) ) {
			$validity->add( 'invalid_alignment', __( 'Invalid alignment selection.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Validate image URL.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_image_url( $validity, $value ) {
		if ( ! empty( $value ) && ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$validity->add( 'invalid_url', __( 'Please enter a valid image URL.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize width value.
	 *
	 * @param mixed $value The width value to sanitize.
	 * @return int Sanitized width value.
	 */
	public function sanitize_width_value( $value ) {
		$value = absint( $value );
		return ( $value >= 300 && $value <= 800 ) ? $value : 600;
	}

	/**
	 * Validate width value.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param mixed    $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_width_value( $validity, $value ) {
		$value = absint( $value );
		if ( $value < 300 || $value > 800 ) {
			$validity->add( 'invalid_width', __( 'Email width must be between 300 and 800 pixels.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}

	/**
	 * Sanitize border style.
	 *
	 * @param string $value The border style value to sanitize.
	 * @return string Sanitized border style.
	 */
	public function sanitize_border_style( $value ) {
		$allowed_styles = array( 'solid', 'dotted', 'dashed', 'double', 'none' );
		return in_array( $value, $allowed_styles, true ) ? $value : 'solid';
	}

	/**
	 * Validate border style.
	 *
	 * @param WP_Error $validity The validity object.
	 * @param string   $value    The value to validate.
	 * @return WP_Error The validity object.
	 */
	public function validate_border_style( $validity, $value ) {
		$allowed_styles = array( 'solid', 'dotted', 'dashed', 'double', 'none' );
		if ( ! in_array( $value, $allowed_styles, true ) ) {
			$validity->add( 'invalid_border_style', __( 'Invalid border style selection.', 'email-customizer-for-woocommerce' ) );
		}
		return $validity;
	}
}
