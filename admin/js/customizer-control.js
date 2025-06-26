/**
 * Email Customizer Controls JavaScript
 * Add this to your plugin's assets/js/customizer-controls.js file
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {

        // Add custom save button functionality
        var get_preview_url = woocommerce_email_customizer_controls_local.previewUrl;
        var email_trigger = woocommerce_email_customizer_controls_local.email_trigger;
        var panel_id = woocommerce_email_customizer_controls_local.panel_id;
        var iframe_url = '';
        
        wp.customize.panel(panel_id).expanded.bind(function(isExpanded) {
            const iframe = document.querySelector('iframe[title="Site Preview"]');
            if (!iframe) {
                throw new Error('Preview iframe not found');
            }

            let baseUrl = iframe.getAttribute('data-src') || iframe.src;
            if (!baseUrl) {
                throw new Error('Invalid iframe source');
            }

            let url = new URL(baseUrl);
            iframe_url = url.toString();
            console.log(get_preview_url);

            if (isExpanded) {
                // Switch preview URL to email preview
                wp.customize.previewer.previewUrl.set(get_preview_url);
                iframe.src = get_preview_url;
                iframe.setAttribute('data-src', get_preview_url); // <-- Add this if needed
            } else {
                // Revert to original preview
                wp.customize.previewer.previewUrl.set(iframe_url);
                iframe.src = iframe_url;
                iframe.setAttribute('data-src', iframe_url); // <-- Properly sets it
            }
        });
            
            // Live preview for postMessage transport
            wp.customize('wc_email_templates', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
                    }
                });
            });
            wp.customize('wc_email_text', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
                    }
                });
            });
            
            wp.customize('wc_email_header', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
                    }
                });
            });
            
            wp.customize('wc_email_appearance_customizer', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
                    }
                });
            });
            
            wp.customize('wc_email_body', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
                    }
                });
            });

            wp.customize('wc_email_footer', function(value) {
                value.bind(function(newVal) {
                    if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                        wp.customize.previewer.refresh();
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

})(jQuery);