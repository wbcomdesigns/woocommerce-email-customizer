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

            // Show specified controls
            // if (templateControls[template].show) {
            //     templateControls[template].show.forEach(function(controlId) {
            //         wp.customize.control(controlId).activate();
            //     });
            // }
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

})(jQuery);