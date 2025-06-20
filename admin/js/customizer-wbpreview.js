/**
 * Email Customizer Live Preview JavaScript
 * Add this to your plugin's assets/js/customizer-preview.js file
 */

(function($) {
    'use strict';

    $(document).ready(function(){
  // List all available settings
// console.log('All settings:', Object.keys(wp.customize.settings.settings));

// // // Or check all settings
// console.log(Object.keys(wp.customize.settings.controls));
    let argument_obj = {};
    argument_obj['nonce'] = woocommerce_email_customizer_controls_local.ajaxSendEmailNonce;
    

    // Text Settings - Real-time updates
    wp.customize('woocommerce_email_heading_text', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_heading_text'] = newval;
           updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_subheading_text', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_subheading_text'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_text', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_body_text'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_text', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_text'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    // Color Settings - Real-time updates
    wp.customize('woocommerce_email_background_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_background_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_background_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_body_background_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_header_background_color', function(value) {
        value.bind(function(newval) {
             argument_obj['woocommerce_email_header_background_color'] = newval;
               updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_header_text_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_header_text_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_text_color', function (value) {
        value.bind(function (newval) {
            argument_obj['woocommerce_email_body_text_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_link_color', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_link_color'] = newval;
             updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_text_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_text_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_background_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_background_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_border_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_border_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_border_color', function(value) {
        value.bind(function(newval) {
             argument_obj['woocommerce_email_body_border_color'] = newval;
               updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_address_border_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_address_border_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_address_background_color', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_address_background_color'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    // Font Size Settings - Real-time updates
    wp.customize('woocommerce_email_header_font_size', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_header_font_size'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_font_size', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_body_font_size'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_body_title_font_size', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_body_title_font_size'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_font_size', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_font_size'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    // Font Family Settings - Real-time updates
    wp.customize('woocommerce_email_font_family', function(value) {
        value.bind(function(newval) {
             argument_obj['woocommerce_email_font_family'] = newval;
               updateEmailPreviewFrame(argument_obj);
        });
    });

    // Padding Settings - Real-time updates
    wp.customize('woocommerce_email_padding_container_top', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_padding_container_top'] = newval;
              updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_padding_container_bottom', function(value) {
        value.bind(function(newval) {
             argument_obj['woocommerce_email_padding_container_bottom'] = newval;
             updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_padding_container_left_right', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_padding_container_left_right'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    // Footer Padding Settings
    wp.customize('woocommerce_email_footer_top_padding', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_top_padding'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_bottom_padding', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_bottom_padding'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_left_right_padding', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_footer_left_right_padding'] = newval;
           updateEmailPreviewFrame(argument_obj);
        });
    });

    // Border Settings - Real-time updates
    wp.customize('woocommerce_email_border_container_top', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_border_container_top'] = newval;
           updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_border_container_bottom', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_border_container_bottom'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_border_container_left', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_border_container_left'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_border_container_right', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_border_container_right'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_address_border', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_address_border'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_footer_address_border_style', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_footer_address_border_style'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    // Width Settings - Real-time updates
    wp.customize('woocommerce_email_width', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_width'] = newval;
           updateEmailPreviewFrame(argument_obj);
        });
    });

    // Rounded Corners - Real-time updates
    wp.customize('woocommerce_email_rounded_corners', function(value) {
        value.bind(function(newval) {
           argument_obj['woocommerce_email_rounded_corners'] = newval;
           updateEmailPreviewFrame(argument_obj);
        });
    });

    // Box Shadow - Real-time updates
    wp.customize('woocommerce_email_box_shadow_spread', function(value) {
        value.bind(function(newval) {
            var boxShadow = '0 0 ' + Math.abs(newval) + 'px rgba(0,0,0,0.1)';
            if (newval < 0) {
                boxShadow = 'none';
            }
             argument_obj['woocommerce_email_box_shadow_spread'] = boxShadow;
             updateEmailPreviewFrame(argument_obj);
        });
    });

    // Header Image Settings - Real-time updates
    wp.customize('woocommerce_email_header_image', function(value) {
        value.bind(function(newval) {
             argument_obj['woocommerce_email_header_image'] = newval;
             updateEmailPreviewFrame(argument_obj);
        });
    });

    wp.customize('woocommerce_email_header_image_alignment', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_header_image_alignment'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    // Header Image Placement - Real-time updates
    wp.customize('woocommerce_email_header_image_placement', function(value) {
        value.bind(function(newval) {
            argument_obj['woocommerce_email_header_image_placement'] = newval;
            updateEmailPreviewFrame(argument_obj);
        });
    });

    // Template Selection - Real-time updates with settings synchronization
    wp.customize('woocommerce_email_template', function(value) {
        value.bind(function(newval) {
            console.log(newval);
            argument_obj = {};
            argument_obj['nonce'] = woocommerce_email_customizer_controls_local.ajaxSendEmailNonce;
            argument_obj['woocommerce_email_template'] = newval;
            argument_obj['woocommerce_email_padding_container_top'] = '15';
            argument_obj['woocommerce_email_padding_container_bottom'] = '15';
            argument_obj['woocommerce_email_padding_container_left_right'] = '20';
            argument_obj['woocommerce_email_border_color'] = '#cccccc';
            argument_obj['woocommerce_email_header_image_placement'] = 'inside';
            argument_obj['woocommerce_email_header_image'] = '';
            argument_obj['woocommerce_email_header_font_size'] = '18';
            argument_obj['woocommerce_email_header_image_alignment'] = 'center';
            argument_obj['woocommerce_email_background_color'] = '#f7f7f7';
            argument_obj['woocommerce_email_link_color'] = '#0073aa';
            argument_obj['woocommerce_email_body_text_color'] = '#333333';
            argument_obj['woocommerce_email_body_font_size'] = '14';
            argument_obj['woocommerce_email_body_title_font_size'] = '20';
            argument_obj['woocommerce_email_width'] = '600';
            argument_obj['woocommerce_email_font_family'] = 'sans-serif';
            argument_obj['woocommerce_email_box_shadow_spread'] = '0';
            argument_obj['woocommerce_email_footer_font_size'] = '12';
            argument_obj['woocommerce_email_footer_top_padding'] = '15';
            argument_obj['woocommerce_email_footer_bottom_padding'] = '15';
            argument_obj['woocommerce_email_footer_left_right_padding'] = '20';
            argument_obj['woocommerce_email_footer_address_border_color'] = '#dddddd';
            argument_obj['woocommerce_email_footer_address_border_style'] = 'solid';
            
            // Define template configurations
            let templateConfig = {};
            //  let defaults = {};
            if ('template-three' === newval) {
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
            } else if ('template-two' === newval) {
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
            } else if ('template-one' === newval) {
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
            } else {
                // Default template
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
            }
           
            // Update argument object with template configuration
            Object.assign(argument_obj, templateConfig);
            
            // Update the email preview frame
            var iframe = updateEmailPreviewFrame(argument_obj);
            iframe.addEventListener('load', function() {
                // Add your JavaScript code here to run after the iframe loads
                delete argument_obj['woocommerce_email_template'];
                // Update WordPress Customizer settings in real-time
                Object.keys(argument_obj).forEach(function(settingKey) {
                    const setting = wp.customize(settingKey);
                    if (setting) {
                        // Set the value without triggering the callback to avoid infinite loops
                        setting.set(argument_obj[settingKey]);

                    }
                });
                // Trigger preview refresh for this specific setting
                // wp.customize('woocommerce_email_template').preview();
            });
            // Add template class to body for styling purposes
            // wp.customize.preview.send('template-changed', {
            //     template: newval,
            //     config: templateConfig
            // });
            
            // // Optional: Add visual feedback that settings are being updated
            if (typeof console !== 'undefined') {
                console.log(woocommerce_email_customizer_controls_local.template_changed + ' ' + newval);
                console.log(woocommerce_email_customizer_controls_local.settings_updated , argument_obj);
            }
        });
    });


    function updateEmailPreviewFrame(args = {}) {
        const iframe = document.querySelector('iframe[title="Site Preview"]');
        // console.log(args);
        if (!iframe) return;

        let baseUrl = iframe.getAttribute('data-src') || iframe.src;
        let url = new URL(baseUrl);

        // Update the query parameters
        Object.keys(args).forEach((key) => {
            url.searchParams.set(key, args[key]);
        });

        // Set new URL to iframe src
        iframe.src = url.toString();
        return iframe;
    }

      })

})(jQuery);