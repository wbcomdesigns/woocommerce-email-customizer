/**
 * Email Customizer Controls JavaScript
 * Add this to your plugin's assets/js/customizer-controls.js file
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {
        
        // Handle template selection changes
        wp.customize('woocommerce_email_template', function(setting) {
            setting.bind(function(value) {
                // Show/hide controls based on template selection
                toggleControlsBasedOnTemplate(value);
                
                // Update default values based on template
                updateDefaultsBasedOnTemplate(value);
            });
        });

        // Handle header image placement changes
        wp.customize('woocommerce_email_header_image_placement', function(setting) {
            setting.bind(function(value) {
                // Show/hide related controls
                if (value === '') {
                    wp.customize.control('wc_email_header_image_control').deactivate();
                    wp.customize.control('wc_email_header_image_alignment_control').deactivate();
                } else {
                    wp.customize.control('wc_email_header_image_control').activate();
                    wp.customize.control('wc_email_header_image_alignment_control').activate();
                }
            });
        });

        // Add reset functionality
        $('.customize-control').on('click', '.reset-to-default', function(e) {
            e.preventDefault();
            var control = $(this).closest('.customize-control');
            var settingId = control.attr('id').replace('customize-control-', '');
            
            if (wp.customize.has(settingId)) {
                var setting = wp.customize(settingId);
                var defaultValue = setting._value; // Get default value
                setting.set(defaultValue);
            }
        });

        // Initialize controls on load
        var currentTemplate = wp.customize('woocommerce_email_template').get();
        if (currentTemplate) {
            toggleControlsBasedOnTemplate(currentTemplate);
        }
    });

    /**
     * Toggle controls visibility based on selected template
     */
    function toggleControlsBasedOnTemplate(template) {
        // Define which controls should be visible for each template
        var templateControls = {
            'default': {
                show: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control'],
                hide: []
            },
            'template-one': {
                show: ['wc_email_header_border_container_top_control'],
                hide: ['wc_email_rounded_corners_control']
            },
            'template-two': {
                show: ['wc_email_body_border_color_control'],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            },
            'template-three': {
                show: ['wc_email_header_color_control'],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            }
        };

        // Reset all controls to visible first
        $('.customize-control').show();

        // Apply template-specific visibility
        if (templateControls[template]) {
            // Hide specified controls
            if (templateControls[template].hide) {
                templateControls[template].hide.forEach(function(controlId) {
                    wp.customize.control(controlId).deactivate();
                });
            }

            // Show specified controls
            if (templateControls[template].show) {
                templateControls[template].show.forEach(function(controlId) {
                    wp.customize.control(controlId).activate();
                });
            }
        }
    }

    /**
     * Update default values based on template selection
     */
    function updateDefaultsBasedOnTemplate(template) {
        var templateDefaults = {
            'template-three': {
                'woocommerce_email_header_text_color': '#32373c',
                'woocommerce_email_body_background_color': '#ffd2d3',
                'woocommerce_email_header_background_color': '#ffd2d3',
                'woocommerce_email_footer_text_color': '#ffffff',
                'woocommerce_email_footer_background_color': '#202020'
            },
            'template-two': {
                'woocommerce_email_header_text_color': '#32373c',
                'woocommerce_email_body_background_color': '#ffffff',
                'woocommerce_email_header_background_color': '#ffffff',
                'woocommerce_email_footer_text_color': '#202020',
                'woocommerce_email_footer_background_color': '#ffffff'
            },
            'template-one': {
                'woocommerce_email_header_text_color': '#32373c',
                'woocommerce_email_body_background_color': '#ffffff',
                'woocommerce_email_header_background_color': '#ffffff',
                'woocommerce_email_footer_text_color': '#ffffff',
                'woocommerce_email_footer_background_color': '#202020'
            },
            'default': {
                'woocommerce_email_header_text_color': '#ffffff',
                'woocommerce_email_body_background_color': '#fdfdfd',
                'woocommerce_email_header_background_color': '#557da1',
                'woocommerce_email_footer_text_color': '#ffffff',
                'woocommerce_email_footer_background_color': '#202020'
            }
        };

        // Apply template defaults
        if (templateDefaults[template]) {
            Object.keys(templateDefaults[template]).forEach(function(settingId) {
                if (wp.customize.has(settingId)) {
                    wp.customize(settingId).set(templateDefaults[template][settingId]);
                }
            });
        }
    }

    /**
     * Add custom validation for email settings
     */
    wp.customize.bind('ready', function() {
        // Validate email address if there's an email field
        wp.customize('woocommerce_email_send_to', function(setting) {
            setting.bind(function(value) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var control = wp.customize.control('wc_email_send_to_control');
                
                if (value && !emailRegex.test(value)) {
                    control.container.addClass('customize-control-invalid');
                    setting.notifications.add('invalid_email', new wp.customize.Notification('invalid_email', {
                        type: 'error',
                        message: 'Please enter a valid email address.'
                    }));
                } else {
                    control.container.removeClass('customize-control-invalid');
                    setting.notifications.remove('invalid_email');
                }
            });
        });

        // Add live preview toggle
        var previewToggle = $('<button type="button" class="button button-secondary" id="toggle-live-preview">Toggle Live Preview</button>');
        $('.wp-full-overlay-sidebar-content').prepend(previewToggle);
        
        previewToggle.on('click', function() {
            var $preview = $('#customize-preview');
            if ($preview.hasClass('live-preview-disabled')) {
                $preview.removeClass('live-preview-disabled');
                $(this).text('Disable Live Preview');
            } else {
                $preview.addClass('live-preview-disabled');
                $(this).text('Enable Live Preview');
            }
        });
    });

    /**
     * Add section dependencies
     */
    wp.customize.bind('ready', function() {
        // Show email template section only when email customizer is enabled
        wp.customize('woocommerce_email_template', function(setting) {
            wp.customize.section('wc_email_templates').active.bind(function(isActive) {
                // Handle section visibility logic
            });
        });
    });

})(jQuery);