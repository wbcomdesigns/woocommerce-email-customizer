(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
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

    $(document).on('click', '.button-secondary-reset', function () {
      var selectedTemplate = wp.customize('woocommerce_email_template')();
      $.ajax({
      type: "post",
      url: wc_email_customizer_email_ajx.ajaxurl,
      data: {
        'action': 'wb_email_customizer_load_template_presets',
        'template': selectedTemplate,
        'nonce':  wc_email_customizer_email_ajx.nonce
      },
      success: function (res) {
        console.log(res);
        if (true === res.success) {
          window.location.reload();
        }
      },
    });

    });


  });
})(jQuery);
