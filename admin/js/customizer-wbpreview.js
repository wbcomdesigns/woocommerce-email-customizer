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

    // Template Selection - Real-time updates (requires page refresh for full effect)
    wp.customize('woocommerce_email_template', function(value) {
        value.bind(function(newval) {
            argument_obj    = {};
            argument_obj['nonce'] = woocommerce_email_customizer_controls_local.ajaxSendEmailNonce;
            argument_obj['woocommerce_email_template'] = newval;
            updateEmailPreviewFrame(argument_obj);
            // Add a class to body to indicate template change
            // $('body').removeClass('template-default template-one template-two template-three')
                    //  .addClass('template-' + newval);
            
            // You might want to trigger a partial refresh here for complex template changes
            // wp.customize.preview.send('template-changed', newval);
        });
    });


    function updateEmailPreviewFrame(args = {}) {
        const iframe = document.querySelector('iframe[title="Site Preview"]');
        console.log(iframe);
        if (!iframe) return;

        let baseUrl = iframe.getAttribute('data-src') || iframe.src;
        let url = new URL(baseUrl);

        // Update the query parameters
        Object.keys(args).forEach((key) => {
            url.searchParams.set(key, args[key]);
        });

        // Set new URL to iframe src
        iframe.src = url.toString();
    }

      })

})(jQuery);