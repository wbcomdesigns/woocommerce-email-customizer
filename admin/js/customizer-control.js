/**
 * Email Customizer Controls JavaScript
 * Add this to your plugin's assets/js/customizer-controls.js file
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {

        // Add custom save button functionality

        wp.customize.section('wc_email_templates', function(section) {
                section.expanded.bind(function(isExpanded) {
                    if (isExpanded) {
                        $('#customize-save-button-wrapper').hide();
                        setupCustomSaveButton();
                    } else {
                        $('#customize-save-button-wrapper').show();
                    }
                });
            });
        
        // Handle template selection changes
        wp.customize('woocommerce_email_template', function(setting) {
            setting.bind(function(value) {
                // Show/hide controls based on template selection
                toggleControlsBasedOnTemplate(value);
                
                // Update default values based on template
                // updateDefaultsBasedOnTemplate(value);
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
        // var currentTemplate = wp.customize('woocommerce_email_template').get();
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
                show: [''],
                hide: ['wc_email_rounded_corners_control']
            },
            'template-two': {
                show: [''],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            },
            'template-three': {
                show: [''],
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
        }
    }

    /**
     * Add section dependencies
     */
    wp.customize.bind('ready', function() {
      
      wp.customize.section('wc_email_header', function(section) {
            section.expanded.bind(function(isExpanded) {
                if (isExpanded) {
                    // console.log('Email Header section opened');

                    // Now access your setting
                    if (wp.customize.has('woocommerce_email_header_image_placement')) {
                        var setting = wp.customize('woocommerce_email_header_image_placement');

                        function toggleHeaderImageControls(value) {
                            if (wp.customize.control.has('wc_email_header_image_control')) {
                                var control1 = wp.customize.control('wc_email_header_image_control');
                                var control2 = wp.customize.control('wc_email_header_image_alignment_control');

                                if (!value) {
                                    control1.container.hide();
                                    control2.container.hide();
                                } else {
                                    control1.container.show();
                                    control2.container.show();
                                }
                            }
                        }

                        // Run once when section opens
                        toggleHeaderImageControls(setting.get());

                        // Listen for future value changes
                        setting.bind(toggleHeaderImageControls);
                    } else {
                        console.warn('woocommerce_email_header_image_placement setting not found');
                    }
                }
            });
        });



        wp.customize('woocommerce_email_header_image_placement', function(setting) {
    
            // Function to toggle controls based on current value
            function toggleHeaderImageControls(value) {
                if (!value) {
                    wp.customize.control('wc_email_header_image_control').deactivate();
                    wp.customize.control('wc_email_header_image_alignment_control').deactivate();
                } else {
                    wp.customize.control('wc_email_header_image_control').activate();
                    wp.customize.control('wc_email_header_image_alignment_control').activate();
                }
            }

            // Run once on load
            toggleHeaderImageControls(setting.get());

            // Also bind to changes for live behavior
            setting.bind(toggleHeaderImageControls);
        });
    });

    /*
    * Check if current section is an email customizer section
    */
    function isEmailCustomizerSection(sectionId) {
        var emailSections = [
            'wc_email_templates',
            'wc_email_text', 
            'wc_email_appearance_customizer',
            'wc_email_header',
            'wc_email_body',
            'wc_email_footer'
        ];
        
        return emailSections.indexOf(sectionId) !== -1;
    }

    /**
     * Setup custom save button functionality
     */
    function setupCustomSaveButton() {
        $(document).on('click', '.wb-email-customizer-save-btn', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $status = $('.wb-save-status');
            
            // Disable button and show loading state
            $button.prop('disabled', true);
            $button.text(woocommerce_email_customizer_controls_local.saving_text);
            $status.hide();
            
            // Collect all email customizer settings
            var customizerData = {};
            
            wp.customize.each(function(setting) {
                var settingId = setting.id;
                
                // Only include email customizer settings
                if (settingId.indexOf('woocommerce_email_') === 0 || 
                    settingId === 'email_customizer_reset_btn') {
                    customizerData[settingId] = setting.get();
                }
            });
            // Send AJAX request
            $.ajax({
                url: woocommerce_email_customizer_controls_local.ajaxurl,
                type: 'POST',
                data: {
                    action: 'wb_email_customizer_save',
                    nonce: woocommerce_email_customizer_controls_local.ajaxSendEmailNonce,
                    customizer_data: JSON.stringify(customizerData)
                },
                success: function(response) {
                    if (response.success) {
                        $status.removeClass('error').addClass('success')
                              .text(woocommerce_email_customizer_controls_local.saved_text)
                              .show().fadeOut(3000);
                        
                        // Mark settings as saved to prevent "unsaved changes" warning
                        wp.customize.each(function(setting) {
                            if (setting.id.indexOf('woocommerce_email_') === 0) {
                                setting._dirty = false;
                            }
                        });
                        
                        // Trigger custom event
                        $(document).trigger('wb-email-customizer-saved', [customizerData]);
                        window.location.reload(); // Reload to apply changes
                    } else {
                        $status.removeClass('success').addClass('error')
                              .text(woocommerce_email_customizer_controls_local.error_text)
                              .show();
                    }
                },
                error: function() {
                    $status.removeClass('success').addClass('error')
                          .text(woocommerce_email_customizer_controls_local.error_text)
                          .show();
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false);
                    $button.text($button.data('original-text') || 'Save Email Settings');
                }
            });
        });
        
        // Store original button text
        $('.wb-email-customizer-save-btn').each(function() {
            $(this).data('original-text', $(this).text());
        });
    }

    /**
     * Handle unsaved changes warning
     */
    wp.customize.bind('saved', function() {
        // This prevents the "unsaved changes" warning when leaving
        wp.customize.each(function(setting) {
            if (setting.id.indexOf('woocommerce_email_') === 0) {
                setting._dirty = false;
            }
        });
    });

    /**
     * Optional: Add keyboard shortcut for saving (Ctrl+S)
     */
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.which === 83) { // Ctrl+S
            var currentSection = wp.customize.section.each(function(section) {
                if (section.expanded() && isEmailCustomizerSection(section.id)) {
                    e.preventDefault();
                    $('.wb-email-customizer-save-btn').trigger('click');
                    return false;
                }
            });
        }
    });

})(jQuery);