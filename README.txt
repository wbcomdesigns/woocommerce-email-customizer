=== Wbcom Designs - WooCommerce Email Customizer ===
Contributors: vapvarun, wbcomdesigns
Donate link: https://wbcomdesigns.com/
Tags: woocommerce, email, customizer, email template, branding
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize your WooCommerce transactional emails with a live preview using the WordPress Customizer. Change colors, fonts, header images, layout, and more.

== Description ==

**WooCommerce Email Customizer** lets you brand your WooCommerce transactional emails right from the WordPress Customizer with a real-time live preview.

No coding required. Simply adjust the controls and see your changes instantly in the preview pane.

= Features =

* **Live Preview** - See your email changes in real time using the WordPress Customizer
* **Header Customization** - Upload a logo/header image, change background and text colors
* **Body Styling** - Customize background colors, text colors, font size, and font family
* **Footer Customization** - Edit footer text, colors, padding, and address block styling
* **Container Layout** - Adjust email width, padding, borders, rounded corners, and box shadow
* **Header Image Options** - Inside or outside placement, left/center/right alignment
* **Test Email** - Send a test email to verify your design looks correct
* **WooCommerce Subscriptions** - Compatible with WooCommerce Subscriptions emails
* **RTL Support** - Full right-to-left language support

= How It Works =

1. Go to **WooCommerce > Email Customizer** (or **Appearance > Customize > Email Customizer**)
2. Adjust the settings in each section (Header, Body, Footer, Container)
3. See your changes live in the preview pane
4. Click **Publish** to save

= Requirements =

* WordPress 5.8 or higher
* WooCommerce 7.0 or higher
* PHP 7.4 or higher

== Installation ==

1. Upload the `woocommerce-email-customizer` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **WooCommerce > Email Customizer** to start customizing

== Frequently Asked Questions ==

= Does this work with all WooCommerce emails? =

Yes, the customizer styles apply to all WooCommerce transactional emails including new order, processing, completed, refunded, and customer note emails.

= Can I upload a header image? =

Yes. Go to the Email Header section in the Customizer and upload your image. You can choose inside or outside placement and left, center, or right alignment.

= Does this work with WooCommerce Subscriptions? =

Yes. If WooCommerce Subscriptions is active, the customizer automatically applies your styles to subscription-related emails as well.

= Can I send a test email? =

Yes. There is a test email section in the Customizer where you can enter an email address and send a preview.

= Will my email clients support these styles? =

The plugin uses inline CSS which is the standard for email client compatibility. It works with Gmail, Outlook, Apple Mail, Yahoo Mail, and most other major email clients.

== Screenshots ==

1. Email Customizer panel in the WordPress Customizer
2. Header customization options with live preview
3. Footer and container styling options

== Changelog ==

= 1.3.0 =
* Fix: Removed orphaned JS enqueue causing 404 error on every Customizer load
* Fix: Plugin now properly checks for WooCommerce before bootstrapping
* Fix: Added isset guard for $additional_content in all email templates
* Fix: Proper wp_unslash() on POST data during test email
* Fix: $_GET superglobal properly restored in all code paths
* Fix: WP_Customize_Control class only loaded in Customizer (performance improvement)
* Removed: Dead code - Cache, Logger, Validator classes (~600 lines)
* Removed: Orphaned methods (control_filter, add_email_header)
* Added: ABSPATH guards on all include files
* Added: Build tooling (gruntfile.js, package.json, .distignore)
* Security: Input sanitization on all $_GET/$_POST/$_REQUEST usage
* Security: Output escaping with wp_kses_post() on email template HTML
* Security: Nonce field sanitization with sanitize_text_field(wp_unslash())
* Performance: N+1 query fix — hoisted get_option calls out of loops
* Performance: Set autoload=no on migration data options
* Fix: Proper sanitization of settings array in Customizer save

= 1.2.0 =
* Fix: Removed view more extension button
* Fix: Hide admin notices and update extension, support title
* Fix: Updated backend UI

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.3.0 =
Important stability update. Fixes 5 critical bugs including a JavaScript 404 error and WooCommerce dependency check. Removes ~600 lines of dead code for better performance.
