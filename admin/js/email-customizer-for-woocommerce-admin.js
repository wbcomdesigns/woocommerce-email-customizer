(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * Updated to work with simplified admin class
   */

  /*faq tab accordion*/
  $(function () {
    var wb_ads_elmt = document.getElementsByClassName("wbcom-faq-accordion");
    var k;
    var wb_ads_elmt_len = wb_ads_elmt.length;
    for (k = 0; k < wb_ads_elmt_len; k++) {
      wb_ads_elmt[k].onclick = function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        }
      };
    }

    // Enhanced reset button handler with better error handling and feedback
    $(document).on('click', '.button-secondary-reset', function (e) {
      e.preventDefault();
      
      var $button = $(this);
      var originalText = $button.text();
      
      // Check if we're in the customizer context and have the required variables
      if (typeof wc_email_customizer_email_ajx === 'undefined') {
        console.warn('Reset button clicked outside customizer context');
        alert('This functionality is only available in the Email Customizer. Please go to WooCommerce > Email Customizer to use this feature.');
        return;
      }
      
      if (!wc_email_customizer_email_ajx.nonce) {
        console.error('Nonce not available');
        alert('Security token missing. Please refresh the page and try again.');
        return;
      }
      
      if (!wc_email_customizer_email_ajx.ajaxurl) {
        console.error('AJAX URL not available');
        alert('AJAX URL missing. Please refresh the page and try again.');
        return;
      }
      
      // Disable button and show loading state
      $button.prop('disabled', true).text('Resetting...');
      
      // Get current template or default to 'default'
      var selectedTemplate = 'default';
      try {
        if (typeof wp !== 'undefined' && wp.customize && wp.customize('woocommerce_email_template')) {
          selectedTemplate = wp.customize('woocommerce_email_template')();
        }
      } catch (error) {
        console.warn('Could not get current template, using default:', error);
      }
      
      console.log('Resetting template to:', selectedTemplate);
      console.log('Available nonce:', wc_email_customizer_email_ajx.nonce);
      console.log('AJAX URL:', wc_email_customizer_email_ajx.ajaxurl);
      
      $.ajax({
        type: "post",
        url: wc_email_customizer_email_ajx.ajaxurl,
        data: {
          'action': 'wb_email_customizer_load_template_presets',
          'template': selectedTemplate,
          'nonce': wc_email_customizer_email_ajx.nonce,
          '_wpnonce': wc_email_customizer_email_ajx.nonce,
          '_ajax_nonce': wc_email_customizer_email_ajx.nonce
        },
        timeout: 30000, // 30 second timeout
        beforeSend: function() {
          console.log('Sending AJAX request with data:', {
            action: 'wb_email_customizer_load_template_presets',
            template: selectedTemplate,
            nonce: wc_email_customizer_email_ajx.nonce ? 'present' : 'missing'
          });
        },
        success: function (res) {
          console.log('Reset response:', res);
          
          if (res.success === true) {
            // Show success message
            $button.text('Reset Complete!').removeClass('button-secondary').addClass('button-primary');
            
            // Reload the page after a short delay
            setTimeout(function() {
              window.location.reload();
            }, 1000);
          } else {
            // Show error message
            console.error('Reset failed:', res);
            $button.text('Reset Failed').addClass('button-error');
            
            // Show user-friendly error
            var errorMsg = res.data || 'Failed to reset template. Please try again.';
            if (typeof wp !== 'undefined' && wp.customize && wp.customize.notifications) {
              wp.customize.notifications.add('reset_error', new wp.customize.Notification(
                'reset_error',
                {
                  message: errorMsg,
                  type: 'error',
                  dismissible: true
                }
              ));
            } else {
              alert(errorMsg);
            }
            
            // Restore button after 3 seconds
            setTimeout(function() {
              $button.prop('disabled', false).text(originalText).removeClass('button-error');
            }, 3000);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX error during reset:', {
            status: status,
            error: error,
            response: xhr.responseText,
            responseJSON: xhr.responseJSON
          });
          
          // Show error state
          $button.text('Network Error').addClass('button-error');
          
          // Show user-friendly error
          var errorMsg = 'Network error occurred. Please check your connection and try again.';
          if (status === 'timeout') {
            errorMsg = 'Request timed out. Please try again.';
          } else if (xhr.responseText) {
            // Try to extract useful error info
            if (xhr.responseText.indexOf('Security check failed') !== -1) {
              errorMsg = 'Security check failed. Please refresh the page and try again.';
            }
          }
          
          if (typeof wp !== 'undefined' && wp.customize && wp.customize.notifications) {
            wp.customize.notifications.add('reset_network_error', new wp.customize.Notification(
              'reset_network_error',
              {
                message: errorMsg,
                type: 'error',
                dismissible: true
              }
            ));
          } else {
            alert(errorMsg);
          }
          
          // Restore button after 3 seconds
          setTimeout(function() {
            $button.prop('disabled', false).text(originalText).removeClass('button-error');
          }, 3000);
        }
      });
    });

    // Add some utility functions for the customizer
    window.WBEmailCustomizer = {
      // Show notification in customizer if available, otherwise use alert
      showNotification: function(message, type) {
        type = type || 'info';
        
        if (typeof wp !== 'undefined' && wp.customize && wp.customize.notifications) {
          var notificationId = 'wb_notification_' + Date.now();
          wp.customize.notifications.add(notificationId, new wp.customize.Notification(
            notificationId,
            {
              message: message,
              type: type,
              dismissible: true
            }
          ));
          
          // Auto-dismiss info and success messages
          if (type === 'info' || type === 'success') {
            setTimeout(function() {
              wp.customize.notifications.remove(notificationId);
            }, 5000);
          }
        } else {
          alert(message);
        }
      },
      
      // Log debug information
      debug: function(message, data) {
        if (window.console && console.log) {
          console.log('[WB Email Customizer] ' + message, data || '');
        }
      },
      
      // Check if we're in the email customizer context
      isEmailCustomizer: function() {
        return window.location.href.indexOf('email-customizer-for-woocommerce') !== -1 ||
               (typeof wp !== 'undefined' && wp.customize && wp.customize.panel && wp.customize.panel('wc_email_customizer_panel'));
      }
    };

    // Initialize debug logging
    WBEmailCustomizer.debug('Admin script loaded', {
      isCustomizer: WBEmailCustomizer.isEmailCustomizer(),
      ajaxUrl: typeof wc_email_customizer_email_ajx !== 'undefined' ? wc_email_customizer_email_ajx.ajaxurl : 'not available',
      nonce: typeof wc_email_customizer_email_ajx !== 'undefined' && wc_email_customizer_email_ajx.nonce ? 'present' : 'missing',
      nonceValue: typeof wc_email_customizer_email_ajx !== 'undefined' ? wc_email_customizer_email_ajx.nonce : 'not available',
      context: window.location.href.indexOf('customize.php') !== -1 ? 'customizer' : 'admin'
    });

    // Add global error handler for AJAX requests
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
      // Only handle our plugin's AJAX requests
      if (settings.data && settings.data.indexOf('wb_email_customizer') !== -1) {
        WBEmailCustomizer.debug('AJAX Error', {
          url: settings.url,
          data: settings.data,
          error: thrownError,
          status: xhr.status,
          responseText: xhr.responseText
        });
      }
    });

  });
})(jQuery);