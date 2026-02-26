/**
 * Email Customizer Live Preview JavaScript
 * Updated to work with simplified admin class without cache
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        let argument_obj = {};
        argument_obj['nonce'] = woocommerce_email_customizer_controls_local.ajaxSendEmailNonce;

        // Debounce function to prevent excessive updates
        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }

        // Optimized preview update function
        const updateEmailPreviewFrame = debounce(function(args) {
            try {
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

                let url = new URL(baseUrl);

                // Update URL parameters
                Object.keys(args).forEach((key) => {
                    if (args[key] !== null && args[key] !== undefined) {
                        url.searchParams.set(key, args[key]);
                    }
                });

                iframe.src = url.toString();
                return iframe;
            } catch (error) {
                console.error('Error updating email preview:', error);
            }
        }, 300); // 300ms debounce

        // Generic function to bind customizer settings
        function bindCustomizerSetting(settingName, callback) {
            try {
                if (wp.customize && wp.customize(settingName)) {
                    wp.customize(settingName, function (value) {
                        value.bind(function(newval) {
                            try {
                                callback(newval);
                            } catch (error) {
                                console.error('Error in setting callback for ' + settingName + ':', error);
                            }
                        });
                    });
                } else {
                    console.warn('Customizer setting not found:', settingName);
                }
            } catch (error) {
                console.error('Error binding customizer setting ' + settingName + ':', error);
            }
        }

        // Text Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_heading_text', function (newval) {
            argument_obj['woocommerce_email_heading_text'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_subheading_text', function (newval) {
            argument_obj['woocommerce_email_subheading_text'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_text', function (newval) {
            argument_obj['woocommerce_email_body_text'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_text', function (newval) {
            argument_obj['woocommerce_email_footer_text'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Color Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_background_color', function (newval) {
            argument_obj['woocommerce_email_background_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_background_color', function (newval) {
            argument_obj['woocommerce_email_body_background_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_header_background_color', function (newval) {
            argument_obj['woocommerce_email_header_background_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_header_text_color', function (newval) {
            argument_obj['woocommerce_email_header_text_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_text_color', function (newval) {
            argument_obj['woocommerce_email_body_text_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_link_color', function (newval) {
            argument_obj['woocommerce_email_link_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_text_color', function (newval) {
            argument_obj['woocommerce_email_footer_text_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_background_color', function (newval) {
            argument_obj['woocommerce_email_footer_background_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_border_color', function (newval) {
            argument_obj['woocommerce_email_border_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_border_color', function (newval) {
            argument_obj['woocommerce_email_body_border_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_address_border_color', function (newval) {
            argument_obj['woocommerce_email_footer_address_border_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_address_background_color', function (newval) {
            argument_obj['woocommerce_email_footer_address_background_color'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Font Size Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_header_font_size', function (newval) {
            argument_obj['woocommerce_email_header_font_size'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_font_size', function (newval) {
            argument_obj['woocommerce_email_body_font_size'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_body_title_font_size', function (newval) {
            argument_obj['woocommerce_email_body_title_font_size'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_font_size', function (newval) {
            argument_obj['woocommerce_email_footer_font_size'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Font Family Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_font_family', function (newval) {
            argument_obj['woocommerce_email_font_family'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Padding Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_padding_container_top', function (newval) {
            argument_obj['woocommerce_email_padding_container_top'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_padding_container_bottom', function (newval) {
            argument_obj['woocommerce_email_padding_container_bottom'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_padding_container_left_right', function (newval) {
            argument_obj['woocommerce_email_padding_container_left_right'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Footer Padding Settings
        bindCustomizerSetting('woocommerce_email_footer_top_padding', function (newval) {
            argument_obj['woocommerce_email_footer_top_padding'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_bottom_padding', function (newval) {
            argument_obj['woocommerce_email_footer_bottom_padding'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_left_right_padding', function (newval) {
            argument_obj['woocommerce_email_footer_left_right_padding'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Border Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_border_container_top', function (newval) {
            argument_obj['woocommerce_email_border_container_top'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_border_container_bottom', function (newval) {
            argument_obj['woocommerce_email_border_container_bottom'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_border_container_left', function (newval) {
            argument_obj['woocommerce_email_border_container_left'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_border_container_right', function (newval) {
            argument_obj['woocommerce_email_border_container_right'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_address_border', function (newval) {
            argument_obj['woocommerce_email_footer_address_border'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_footer_address_border_style', function (newval) {
            argument_obj['woocommerce_email_footer_address_border_style'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Width Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_width', function (newval) {
            argument_obj['woocommerce_email_width'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Rounded Corners - Real-time updates
        bindCustomizerSetting('woocommerce_email_rounded_corners', function (newval) {
            argument_obj['woocommerce_email_rounded_corners'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Box Shadow - Real-time updates
        bindCustomizerSetting('woocommerce_email_box_shadow_spread', function (newval) {
            argument_obj['woocommerce_email_box_shadow_spread'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Header Image Settings - Real-time updates
        bindCustomizerSetting('woocommerce_email_header_image', function (newval) {
            argument_obj['woocommerce_email_header_image'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_header_image_alignment', function (newval) {
            argument_obj['woocommerce_email_header_image_alignment'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Header Image Placement - Real-time updates
        bindCustomizerSetting('woocommerce_email_header_image_placement', function (newval) {
            argument_obj['woocommerce_email_header_image_placement'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Social Media Links - Real-time updates
        var socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
        socialPlatforms.forEach(function(platform) {
            bindCustomizerSetting('woocommerce_email_social_' + platform, function (newval) {
                argument_obj['woocommerce_email_social_' + platform] = newval;
                updateEmailPreviewFrame(argument_obj);
            });
        });

        bindCustomizerSetting('woocommerce_email_social_alignment', function (newval) {
            argument_obj['woocommerce_email_social_alignment'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Custom CSS - Real-time updates
        bindCustomizerSetting('woocommerce_email_custom_css', function (newval) {
            argument_obj['woocommerce_email_custom_css'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Email Preview Options — Email Type and Order Selector
        bindCustomizerSetting('woocommerce_email_preview_type', function (newval) {
            argument_obj['woocommerce_email_preview_type'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        bindCustomizerSetting('woocommerce_email_preview_order', function (newval) {
            argument_obj['woocommerce_email_preview_order'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });

        // Template Selection - Real-time updates with settings synchronization
        wp.customize('woocommerce_email_template', function (value) {
            value.bind(function (newval) {
                // Reset argument object for template change
                argument_obj = {};
                argument_obj['nonce'] = woocommerce_email_customizer_controls_local.ajaxSendEmailNonce;

                // Set base defaults
                var baseDefaults = {
                    'woocommerce_email_padding_container_top': '15',
                    'woocommerce_email_padding_container_bottom': '15',
                    'woocommerce_email_padding_container_left_right': '20',
                    'woocommerce_email_border_color': '#cccccc',
                    'woocommerce_email_header_image_placement': 'inside',
                    'woocommerce_email_header_image': '',
                    'woocommerce_email_header_font_size': '18',
                    'woocommerce_email_header_image_alignment': 'center',
                    'woocommerce_email_background_color': '#f7f7f7',
                    'woocommerce_email_link_color': '#0073aa',
                    'woocommerce_email_body_text_color': '#333333',
                    'woocommerce_email_body_font_size': '14',
                    'woocommerce_email_body_title_font_size': '20',
                    'woocommerce_email_width': '600',
                    'woocommerce_email_font_family': 'sans-serif',
                    'woocommerce_email_box_shadow_spread': '0',
                    'woocommerce_email_footer_font_size': '12',
                    'woocommerce_email_footer_top_padding': '15',
                    'woocommerce_email_footer_bottom_padding': '15',
                    'woocommerce_email_footer_left_right_padding': '20',
                    'woocommerce_email_footer_address_border_color': '#dddddd',
                    'woocommerce_email_footer_address_border_style': 'solid'
                };

                // Apply base defaults
                Object.assign(argument_obj, baseDefaults);

                // Define template-specific configurations
                var templateConfig = {};
                
                switch(newval) {
                    case 'template-three':
                        templateConfig = {
                            'woocommerce_email_header_text_color': '#32373c',
                            'woocommerce_email_body_background_color': '#ffd2d3',
                            'woocommerce_email_header_background_color': '#ffd2d3',
                            'woocommerce_email_footer_address_background_color': '#ffd2d3',
                            'woocommerce_email_footer_address_border': '2',
                            'woocommerce_email_rounded_corners': '0',
                            'woocommerce_email_border_container_top': '0',
                            'woocommerce_email_border_container_bottom': '0',
                            'woocommerce_email_border_container_left': '0',
                            'woocommerce_email_border_container_right': '0',
                            'woocommerce_email_body_border_color': '#505050',
                            'woocommerce_email_footer_text_color': '#ffffff',
                            'woocommerce_email_footer_background_color': '#202020'
                        };
                        break;
                    case 'template-two':
                        templateConfig = {
                            'woocommerce_email_header_text_color': '#32373c',
                            'woocommerce_email_body_background_color': '#ffffff',
                            'woocommerce_email_header_background_color': '#ffffff',
                            'woocommerce_email_footer_address_background_color': '#ffffff',
                            'woocommerce_email_footer_address_border': '1',
                            'woocommerce_email_rounded_corners': '0',
                            'woocommerce_email_border_container_top': '0',
                            'woocommerce_email_border_container_bottom': '0',
                            'woocommerce_email_border_container_left': '0',
                            'woocommerce_email_border_container_right': '0',
                            'woocommerce_email_body_border_color': '#dddddd',
                            'woocommerce_email_footer_text_color': '#202020',
                            'woocommerce_email_footer_background_color': '#ffffff'
                        };
                        break;
                    case 'template-one':
                        templateConfig = {
                            'woocommerce_email_header_text_color': '#32373c',
                            'woocommerce_email_body_background_color': '#ffffff',
                            'woocommerce_email_header_background_color': '#ffffff',
                            'woocommerce_email_footer_address_background_color': '#ffffff',
                            'woocommerce_email_footer_address_border': '1',
                            'woocommerce_email_rounded_corners': '0',
                            'woocommerce_email_border_container_top': '0',
                            'woocommerce_email_border_container_bottom': '0',
                            'woocommerce_email_border_container_left': '0',
                            'woocommerce_email_border_container_right': '0',
                            'woocommerce_email_body_border_color': '#f6f6f6',
                            'woocommerce_email_footer_text_color': '#ffffff',
                            'woocommerce_email_footer_background_color': '#202020'
                        };
                        break;
                    default: // 'default' template
                        templateConfig = {
                            'woocommerce_email_header_text_color': '#ffffff',
                            'woocommerce_email_body_background_color': '#fdfdfd',
                            'woocommerce_email_header_background_color': '#557da1',
                            'woocommerce_email_footer_address_background_color': '#ffffff',
                            'woocommerce_email_footer_address_border': '1',
                            'woocommerce_email_rounded_corners': '6',
                            'woocommerce_email_border_container_top': '1',
                            'woocommerce_email_border_container_bottom': '1',
                            'woocommerce_email_border_container_left': '1',
                            'woocommerce_email_border_container_right': '1',
                            'woocommerce_email_body_border_color': '#505050',
                            'woocommerce_email_footer_text_color': '#ffffff',
                            'woocommerce_email_footer_background_color': '#202020'
                        };
                        break;
                }

                // Apply template configuration
                Object.assign(argument_obj, templateConfig);

                // Update customizer controls to reflect new values
                Object.keys(argument_obj).forEach(function (settingKey) {
                    if (settingKey !== 'nonce') {
                        var setting = wp.customize(settingKey);
                        if (setting) {
                            setting.set(argument_obj[settingKey]);
                        }
                    }
                });

                // Set the template value
                argument_obj['woocommerce_email_template'] = newval;

                // Update the email preview frame
                updateEmailPreviewFrame(argument_obj);

                // Log the change
                console.log(woocommerce_email_customizer_controls_local.template_changed + ' ' + newval);
                console.log(woocommerce_email_customizer_controls_local.settings_updated, argument_obj);
            });
        });

        // Global error handler
        window.addEventListener('error', function(e) {
            if (e.filename && e.filename.indexOf('customizer-wbpreview') !== -1) {
                console.error('Email Customizer Preview Error:', {
                    message: e.message,
                    filename: e.filename,
                    lineno: e.lineno,
                    colno: e.colno,
                    error: e.error
                });
            }
        });

        console.log('Email Customizer Preview initialized successfully');
    });

})(jQuery);