# WooCommerce Email Customizer

## Plugin Identity
- **Name:** Wbcom Designs - Woocommerce Email Customizer
- **Slug:** `email-customizer-for-woocommerce`
- **Version:** 1.3.0
- **Author:** Wbcom Designs
- **License:** GPL-2.0+
- **Text Domain:** `email-customizer-for-woocommerce`
- **Plugin URI:** https://wbcomdesigns.com/downloads/email-customizer-for-woocommerce
- **Monetization:** EDD License (paid plugin, not on WordPress.org free directory)
- **WP Requires:** 5.8+
- **Tested Up To:** WP 6.9

## Codebase Stats
- **Total PHP Lines:** ~7,200 (after dead code removal)
- **Total Files:** ~66 (excluding .git)
- **Architecture:** WordPress Plugin Boilerplate pattern (admin/public/includes separation)

## Architecture

### Entry Point
- `woocommerce-email-customizer.php` - Bootstrap file, defines constants, activation/deactivation hooks, WooCommerce dependency check

### Core Classes
| File | Class | Purpose |
|------|-------|---------|
| `includes/class-email-customizer-for-woocommerce.php` | `Email_Customizer_For_Woocommerce` | Core plugin class, loads dependencies, defines hooks |
| `includes/class-email-customizer-for-woocommerce-loader.php` | `Email_Customizer_For_Woocommerce_Loader` | Hook orchestration (actions/filters registry) |
| `includes/class-email-customizer-for-woocommerce-i18n.php` | `Email_Customizer_For_Woocommerce_I18n` | Internationalization |
| `includes/class-email-customizer-for-woocommerce-activator.php` | `Email_Customizer_For_Woocommerce_Activator` | Activation logic |
| `includes/class-email-customizer-for-woocommerce-deactivator.php` | `Email_Customizer_For_Woocommerce_Deactivator` | Deactivation logic |
| `includes/class-subscription-handler.php` | `Email_Customizer_Subscription_Handler` | WooCommerce Subscriptions integration |

### Admin Classes
| File | Class | Purpose |
|------|-------|---------|
| `admin/class-email-customizer-for-woocommerce-admin.php` | `Email_Customizer_For_Woocommerce_Admin` | Main admin class (~2000 lines), Customizer integration, AJAX handlers, CSS generation |
| `admin/wbcom/wbcom-admin-settings.php` | - | Wbcom shared admin UI framework |
| `admin/wbcom/wbcom-paid-plugin-settings.php` | - | Wbcom license/paid plugin settings |

### Public Class
| File | Class | Purpose |
|------|-------|---------|
| `public/class-email-customizer-for-woocommerce-public.php` | `Email_Customizer_For_Woocommerce_Public` | Frontend enqueue (minimal) |

### Templates
| File | Purpose |
|------|---------|
| `admin/partials/email-customizer-for-woocommerce-admin-display.php` | Default email template |
| `admin/partials/email-customizer-for-woocommerce-admin-display-template-one.php` | Modern template |
| `admin/partials/email-customizer-for-woocommerce-admin-display-template-two.php` | Minimal template |
| `admin/partials/email-customizer-for-woocommerce-admin-display-template-three.php` | Bold template |
| `templates/emails/email-header.php` | Overrides WooCommerce email-header.php |
| `admin/partials/wb-email-customizer-welcome-page.php` | Welcome/settings page |
| `admin/partials/wb-email-customizer-faq.php` | FAQ page |

### EDD License
| File | Purpose |
|------|---------|
| `edd-license/edd-plugin-license.php` | License activation/deactivation/check via EDD API |
| `edd-license/EDD_Woo_Email_Customizer_Plugin_Updater.php` | Auto-updater class |

### Assets
- `admin/css/` - Admin + Customizer styles (with RTL and minified versions)
- `admin/js/` - Customizer control, preview, admin scripts (with minified)
- `admin/img/` - Template preview thumbnails (default, full, flat, skinny)
- `public/css/` - Public styles (minimal)
- `public/js/` - Public scripts (minimal)

## Key Hooks & Filters

### Filters (Custom)
| Filter | Location | Purpose |
|--------|----------|---------|
| `wb_email_customizer_settings_config` | Admin class | Modify customizer settings array before registration |
| `wb_email_customizer_templates_for_preview` | Admin class | Add/modify email templates for preview override |

### WooCommerce Filters Used
| Filter | Purpose |
|--------|---------|
| `woocommerce_email_footer_text` | Override footer text |
| `woocommerce_email_styles` | Inject custom CSS into emails |
| `woocommerce_locate_template` | Override email templates |

### WordPress Actions Used
| Action | Purpose |
|--------|---------|
| `customize_register` | Register Customizer panel, sections, controls, settings |
| `customize_preview_init` | Enqueue live preview script |
| `customize_controls_enqueue_scripts` | Enqueue Customizer control scripts |
| `customize_save_after` | Handle custom save operations |
| `template_redirect` | Load email preview in Customizer |
| `admin_menu` | Register admin menus |

### AJAX Actions
| Action | Purpose |
|--------|---------|
| `woocommerce_email_customizer_send_email` | Send test email |
| `wb_email_customizer_load_template_presets` | Load template preset defaults |

## Customizer Settings (35+ settings)

### Sections (Panel: `wc_email_customizer_panel`)
1. **Email Templates** (`wc_email_templates`) - Template selection (4 templates)
2. **Email Content** (`wc_email_text`) - Heading, subheading, body text
3. **Email Header** (`wc_email_header`) - Image, placement, alignment, colors, font size
4. **Container & Layout** (`wc_email_appearance_customizer`) - Background, borders, padding, width, corners, shadow
5. **Email Body** (`wc_email_body`) - Colors, font size, font family
6. **Email Footer** (`wc_email_footer`) - Text, colors, padding, address section styling

### All Customizer Settings
```
woocommerce_email_template
woocommerce_email_heading_text
woocommerce_email_subheading_text
woocommerce_email_body_text
woocommerce_email_header_image
woocommerce_email_header_image_placement (inside/outside)
woocommerce_email_header_image_alignment (left/center/right)
woocommerce_email_header_background_color
woocommerce_email_header_text_color
woocommerce_email_header_font_size
woocommerce_email_background_color
woocommerce_email_body_background_color
woocommerce_email_body_text_color
woocommerce_email_body_border_color
woocommerce_email_body_font_size
woocommerce_email_body_title_font_size
woocommerce_email_link_color
woocommerce_email_font_family (sans-serif/serif)
woocommerce_email_width (500/600/700)
woocommerce_email_rounded_corners
woocommerce_email_box_shadow_spread
woocommerce_email_border_color
woocommerce_email_border_container_top/bottom/left/right
woocommerce_email_padding_container_top/bottom/left_right
woocommerce_email_footer_text
woocommerce_email_footer_font_size
woocommerce_email_footer_text_color
woocommerce_email_footer_background_color
woocommerce_email_footer_top/bottom/left_right_padding
woocommerce_email_footer_address_background_color
woocommerce_email_footer_address_border
woocommerce_email_footer_address_border_color
woocommerce_email_footer_address_border_style (solid/dotted)
```

## Dependencies
- **Required:** WooCommerce (auto-deactivates without it)
- **Optional:** WooCommerce Subscriptions (adds subscription email template support)

## WP Options Used
- All customizer settings stored as individual WP options
- `edd_wbcom_woo_email_customizer_license_key` - License key
- `edd_wbcom_woo_email_customizer_license_status` - License status

## Constants
```php
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION  // '1.3.0'
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_DIR      // Plugin directory with trailing slash
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH  // Plugin directory (no trailing slash)
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL   // Plugin URL with trailing slash
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_BASENAME  // Plugin basename
EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_FILE  // Main plugin file path
EDD_WOO_EMAIL_CUSTOMIZER_STORE_URL    // 'https://wbcomdesigns.com/'
EDD_WOO_EMAIL_CUSTOMIZER_ITEM_NAME   // 'Woocommerce Email Customizer'
```

## Security
- Nonce verification on all AJAX handlers
- `manage_woocommerce` capability checks
- Sanitization callbacks on all Customizer settings
- Input sanitization via `sanitize_text_field`, `sanitize_hex_color`, `absint`, `esc_url_raw`

## Recent Changes
| Version | Changes |
|---------|---------|
| 1.3.0 | Critical bug fixes (5), dead code removal (~600 lines), ABSPATH guards, build tooling, README rewrite, version bump |
| 1.2.0 | Removed view more extension button, hide admin notices, updated backend UI |
| 1.0.0 | Initial Release |
