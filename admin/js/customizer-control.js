/**
 * Email Customizer Controls JavaScript
 * Updated to work with simplified admin class
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {
        // Get localized variables
        var get_preview_url = woocommerce_email_customizer_controls_local.previewUrl;
        var email_trigger = woocommerce_email_customizer_controls_local.email_trigger;
        var panel_id = woocommerce_email_customizer_controls_local.panel_id;
        var iframe_url = '';
        
        // Handle panel expand/collapse to switch preview URLs
        wp.customize.panel(panel_id).expanded.bind(function(isExpanded) {
            const iframe = document.querySelector('iframe[title="Site Preview"]');
            if (!iframe) {
                console.warn('Preview iframe not found');
                return;
            }

            let baseUrl = iframe.getAttribute('data-src') || iframe.src;
            if (!baseUrl) {
                console.warn('Invalid iframe source');
                return;
            }

            try {
                let url = new URL(baseUrl);
                iframe_url = url.toString();

                if (isExpanded) {
                    // Switch to email preview
                    wp.customize.previewer.previewUrl.set(get_preview_url);
                    iframe.src = get_preview_url;
                    iframe.setAttribute('data-src', get_preview_url);
                    console.log('Switched to email preview:', get_preview_url);
                } else {
                    // Revert to original preview
                    wp.customize.previewer.previewUrl.set(iframe_url);
                    iframe.src = iframe_url;
                    iframe.setAttribute('data-src', iframe_url);
                    console.log('Reverted to original preview:', iframe_url);
                }
            } catch (error) {
                console.error('Error switching preview URL:', error);
            }
        });
            
        // Live preview refresh for template changes
        wp.customize('woocommerce_email_template', function(value) {
            value.bind(function(newVal) {
                if (wp.customize.previewer.previewUrl().indexOf(email_trigger) !== -1) {
                    console.log('Template changed to:', newVal);
                    wp.customize.previewer.refresh();
                }
            });
        });

        // Handle template selection changes
        wp.customize('woocommerce_email_template', function(setting) {
            setting.bind(function(value) {
                // Show/hide controls based on template selection
                toggleControlsBasedOnTemplate(value);
                
                // Show notification about template change
                if (wp.customize.notifications) {
                    wp.customize.notifications.add('template_changed', new wp.customize.Notification(
                        'template_changed',
                        {
                            message: woocommerce_email_customizer_controls_local.template_changed + ' ' + value,
                            type: 'info',
                            dismissible: true
                        }
                    ));
                    
                    // Auto-dismiss after 3 seconds
                    setTimeout(function() {
                        wp.customize.notifications.remove('template_changed');
                    }, 3000);
                }
            });
        });

        // Handle header image placement changes
        wp.customize('woocommerce_email_header_image_placement', function(setting) {
            setting.bind(function(value) {
                toggleHeaderImageControls(value);
            });
        });

        // Initialize header image controls on load
        var currentPlacement = wp.customize('woocommerce_email_header_image_placement').get();
        toggleHeaderImageControls(currentPlacement);

        // Handle header section expansion to check placement
        wp.customize.section('wc_email_header', function(section) {
            section.expanded.bind(function(isExpanded) {
                if (isExpanded) {
                    // Re-check header image placement when section opens
                    var placement = wp.customize('woocommerce_email_header_image_placement').get();
                    toggleHeaderImageControls(placement);
                }
            });
        });

        // Add error handling for AJAX operations
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (settings.url && settings.url.indexOf('admin-ajax.php') !== -1) {
                console.error('AJAX Error in email customizer:', {
                    url: settings.url,
                    error: thrownError,
                    response: xhr.responseText
                });
                
                // Show user-friendly error
                if (wp.customize.notifications) {
                    wp.customize.notifications.add('ajax_error', new wp.customize.Notification(
                        'ajax_error',
                        {
                            message: woocommerce_email_customizer_controls_local.error_occurred,
                            type: 'error',
                            dismissible: true
                        }
                    ));
                }
            }
        });
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
                show: [],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            },
            'template-two': {
                show: [],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            },
            'template-three': {
                show: [],
                hide: ['wc_email_rounded_corners_control', 'wc_email_box_shadow_spread_control']
            }
        };

        // Apply template-specific visibility
        if (templateControls[template]) {
            // Show specified controls
            if (templateControls[template].show) {
                templateControls[template].show.forEach(function(controlId) {
                    var control = wp.customize.control(controlId);
                    if (control) {
                        control.activate();
                        control.container.show();
                    }
                });
            }
            
            // Hide specified controls
            if (templateControls[template].hide) {
                templateControls[template].hide.forEach(function(controlId) {
                    var control = wp.customize.control(controlId);
                    if (control) {
                        control.deactivate();
                        control.container.hide();
                    }
                });
            }
        }
    }

    /**
     * Toggle header image related controls based on placement setting
     */
    function toggleHeaderImageControls(value) {
        var imageControl = wp.customize.control('wc_email_header_image_control');
        var alignmentControl = wp.customize.control('wc_email_header_image_alignment_control');

        if (!value || value === '') {
            // Hide image controls when no placement is selected
            if (imageControl) {
                imageControl.deactivate();
                imageControl.container.hide();
            }
            if (alignmentControl) {
                alignmentControl.deactivate();
                alignmentControl.container.hide();
            }
        } else {
            // Show image controls when placement is selected
            if (imageControl) {
                imageControl.activate();
                imageControl.container.show();
            }
            if (alignmentControl) {
                alignmentControl.activate();
                alignmentControl.container.show();
            }
        }
    }

    /**
     * Show success message for operations
     */
    function showSuccessMessage(message) {
        if (wp.customize.notifications) {
            wp.customize.notifications.add('success_message', new wp.customize.Notification(
                'success_message',
                {
                    message: message,
                    type: 'success',
                    dismissible: true
                }
            ));
            
            // Auto-dismiss after 3 seconds
            setTimeout(function() {
                wp.customize.notifications.remove('success_message');
            }, 3000);
        }
    }

    /**
     * Show error message for operations
     */
    function showErrorMessage(message) {
        if (wp.customize.notifications) {
            wp.customize.notifications.add('error_message', new wp.customize.Notification(
                'error_message',
                {
                    message: message,
                    type: 'error',
                    dismissible: true
                }
            ));
        }
    }

    // Global error handler for email customizer
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        if (url && url.indexOf('customizer-control') !== -1) {
            console.error('Email Customizer Error:', {
                message: msg,
                source: url,
                line: lineNo,
                column: columnNo,
                error: error
            });
            return true; // Prevent default error handling
        }
        return false;
    };

})(jQuery);