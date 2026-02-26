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

        // --- Per-Email-Type Content Switching ---
        var emailTypeContent = woocommerce_email_customizer_controls_local.emailTypeContent || {};
        var currentEmailType = '';
        var isTypeSwitching = false; // Guard against race conditions during type switch.

        // Initialize: set currentEmailType from the preview type setting.
        if (wp.customize('woocommerce_email_preview_type')) {
            currentEmailType = wp.customize('woocommerce_email_preview_type').get() || '';
        }

        // When the email type dropdown changes, swap text field content.
        wp.customize('woocommerce_email_preview_type', function(setting) {
            setting.bind(function(newType) {
                isTypeSwitching = true;
                var oldType = currentEmailType;

                // Save current field values under the old type.
                if (oldType) {
                    emailTypeContent[oldType] = {
                        heading: wp.customize('woocommerce_email_heading_text').get() || '',
                        subheading: wp.customize('woocommerce_email_subheading_text').get() || '',
                        body_text: wp.customize('woocommerce_email_body_text').get() || ''
                    };
                }

                currentEmailType = newType;

                // Load the new type's content (or clear for WC defaults).
                if (newType && emailTypeContent[newType]) {
                    var content = emailTypeContent[newType];
                    wp.customize('woocommerce_email_heading_text').set(content.heading || '');
                    wp.customize('woocommerce_email_subheading_text').set(content.subheading || '');
                    wp.customize('woocommerce_email_body_text').set(content.body_text || '');
                } else {
                    // No per-type content for this type — clear fields so WC defaults apply.
                    wp.customize('woocommerce_email_heading_text').set('');
                    wp.customize('woocommerce_email_subheading_text').set('');
                    wp.customize('woocommerce_email_body_text').set('');
                }

                // Update the hidden setting with all per-type data.
                wp.customize('woocommerce_email_type_content').set(JSON.stringify(emailTypeContent));

                isTypeSwitching = false;
            });
        });

        // Pre-load initial email type content into fields on first open.
        if (currentEmailType && emailTypeContent[currentEmailType]) {
            var initContent = emailTypeContent[currentEmailType];
            if (initContent.heading !== undefined) {
                wp.customize('woocommerce_email_heading_text').set(initContent.heading);
            }
            if (initContent.subheading !== undefined) {
                wp.customize('woocommerce_email_subheading_text').set(initContent.subheading);
            }
            if (initContent.body_text !== undefined) {
                wp.customize('woocommerce_email_body_text').set(initContent.body_text);
            }
        }

        // Track text field changes and associate with current email type.
        ['woocommerce_email_heading_text', 'woocommerce_email_subheading_text', 'woocommerce_email_body_text'].forEach(function(settingName) {
            wp.customize(settingName, function(setting) {
                setting.bind(function(newVal) {
                    // Skip updates triggered by the type-switch handler to avoid race conditions.
                    if (isTypeSwitching) {
                        return;
                    }
                    if (currentEmailType) {
                        if (!emailTypeContent[currentEmailType]) {
                            emailTypeContent[currentEmailType] = {};
                        }
                        var fieldKey;
                        if (settingName === 'woocommerce_email_heading_text') {
                            fieldKey = 'heading';
                        } else if (settingName === 'woocommerce_email_subheading_text') {
                            fieldKey = 'subheading';
                        } else {
                            fieldKey = 'body_text';
                        }
                        emailTypeContent[currentEmailType][fieldKey] = newVal;
                        wp.customize('woocommerce_email_type_content').set(JSON.stringify(emailTypeContent));
                    }
                });
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

        // Export settings handler
        $(document).on('click', '#wb-email-export-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.prop('disabled', true).text(woocommerce_email_customizer_controls_local.loading);

            $.ajax({
                url: woocommerce_email_customizer_controls_local.ajaxurl,
                type: 'POST',
                data: {
                    action: woocommerce_email_customizer_controls_local.export_action,
                    nonce: woocommerce_email_customizer_controls_local.importExportNonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        var json = JSON.stringify(response.data, null, 2);
                        var blob = new Blob([json], { type: 'application/json' });
                        var url = URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = 'email-customizer-settings-' + new Date().toISOString().slice(0,10) + '.json';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                        showSuccessMessage(woocommerce_email_customizer_controls_local.export_success);
                    } else {
                        showErrorMessage(response.data || woocommerce_email_customizer_controls_local.error_occurred);
                    }
                },
                error: function() {
                    showErrorMessage(woocommerce_email_customizer_controls_local.error_occurred);
                },
                complete: function() {
                    $btn.prop('disabled', false).text($btn.data('original-text') || 'Download Settings (JSON)');
                }
            });
        });

        // Import settings handler
        $(document).on('click', '#wb-email-import-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $status = $('#wb-email-import-status');
            var fileInput = document.getElementById('wb-email-import-file');

            if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                $status.text(woocommerce_email_customizer_controls_local.import_invalid).css('color', 'red');
                return;
            }

            var file = fileInput.files[0];
            if (!file.name.endsWith('.json')) {
                $status.text(woocommerce_email_customizer_controls_local.import_invalid).css('color', 'red');
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                $btn.prop('disabled', true).text(woocommerce_email_customizer_controls_local.loading);
                $status.text('').css('color', '');

                $.ajax({
                    url: woocommerce_email_customizer_controls_local.ajaxurl,
                    type: 'POST',
                    data: {
                        action: woocommerce_email_customizer_controls_local.import_action,
                        nonce: woocommerce_email_customizer_controls_local.importExportNonce,
                        import_data: event.target.result
                    },
                    success: function(response) {
                        if (response.success) {
                            $status.text(response.data).css('color', 'green');
                            showSuccessMessage(woocommerce_email_customizer_controls_local.import_success);
                            // Refresh after short delay so user sees the message.
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            $status.text(response.data).css('color', 'red');
                            showErrorMessage(response.data);
                        }
                    },
                    error: function() {
                        $status.text(woocommerce_email_customizer_controls_local.error_occurred).css('color', 'red');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Import Settings');
                    }
                });
            };
            reader.readAsText(file);
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