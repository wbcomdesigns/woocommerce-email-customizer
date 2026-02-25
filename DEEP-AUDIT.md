# Deep Audit: WooCommerce Email Customizer v1.2.0
**Date:** 2026-02-25
**Tested On:** wbcom-free.local (WP 6.9.1, WooCommerce active, Storefront theme)

---

## Visual Testing Results

### Admin Pages

| Page | Status | Notes |
|------|--------|-------|
| WB Plugins > WC Email Customizer (Welcome) | PASS | Loads correctly, shows version 1.2.0, 3 resource cards |
| WB Plugins > WC Email Customizer (FAQ) | PASS | 7 FAQ items, accordion works |
| WooCommerce > Email Customizer | PASS | Opens Customizer directly (redirect to customize.php) |
| WP Customizer > Email Customizer Panel | PASS | Panel visible, 6 sections load |

### Customizer Sections

| Section | Status | Notes |
|---------|--------|-------|
| Email Templates | PASS | 4 thumbnails show, selection checkmark works |
| Email Content | UNTESTED | (heading, subheading, body text fields) |
| Email Header | UNTESTED | (image, placement, alignment, colors) |
| Container & Layout | UNTESTED | (width, padding, borders, shadows) |
| Email Body | UNTESTED | (colors, font size, font family) |
| Email Footer | UNTESTED | (text, colors, padding, address styling) |

### Template Rendering

| Template | Status | Notes |
|----------|--------|-------|
| Default | PASS | Steel blue header, white body, black footer. Order table + address block render correctly. |
| Modern (template-one) | PASS | White/black design, "Order Details" section, date shown. Duplicate site name in header + body. |
| Minimal (template-two) | PASS | Clean white, centered layout, "Order Complete" style, address cards. Duplicate site name. |
| Bold (template-three) | PASS | Pink/rose background, separated address blocks. Duplicate site name. |

### Template Switching (Live Preview)

| Test | Status | Notes |
|------|--------|-------|
| Default → Modern | PASS | Preview updates correctly, URL params change |
| Modern → Minimal | PASS | Preview updates correctly |
| Minimal → Bold | PASS | Preview updates correctly |
| Bold → Default | PASS | Preview updates correctly |
| Publish button activates on change | PASS | Button changes from disabled to active |

---

## Console Errors

### JavaScript Errors

| Error | Severity | Location | Details |
|-------|----------|----------|---------|
| **404: customizer.min.js** | CRITICAL | Line 1656 of admin class | `admin/js/min/customizer.min.js` does not exist. Enqueued on `customize_preview_init` hook. The file `customizer.js` also doesn't exist in the unminified folder. This is an orphaned enqueue — the actual working scripts are `customizer-wbpreview.min.js` (line 235) and `customizer-control.min.js` (line 238). |

### JavaScript Warnings

| Warning | Severity | Notes |
|---------|----------|-------|
| iframe sandbox allow-scripts + allow-same-origin | LOW | WP core issue, not plugin-specific. Appears multiple times with template switching. |

### PHP Errors
**None detected** — debug.log clean after page loads with WP_DEBUG enabled.

---

## Code Issues Found

### CRITICAL (Must Fix)

| # | Issue | File | Line | Description |
|---|-------|------|------|-------------|
| C1 | Missing JS file 404 | admin class | 1656 | `customizer.min.js` enqueued but file doesn't exist. Method `wb_email_customizer_enqueue_customizer_script()` loads non-existent `customizer.min.js` on `customize_preview_init`. Every Customizer session produces a 404. |
| C2 | Bootstrap order bug | main plugin file | 170 | `run_email_customizer_for_woocommerce()` called unconditionally BEFORE WC check (line 78). Plugin bootstraps even if WooCommerce isn't active. |
| C3 | $additional_content undefined | all 4 templates | 181/189/219/210 | `$additional_content` used without `isset()` guard. WooCommerce 8.0+ passes this var but older versions don't. |
| C4 | $_POST unsanitized | admin class | 2013 | `$_POST['settings']` not passed through `wp_unslash()` before sanitization. WPCS violation. |
| C5 | $_GET superglobal mutation | admin class | 2021-2034 | `$_GET` modified directly without restoration in the catch branch. Can pollute global state. |

### WARNING (Should Fix)

| # | Issue | File | Line | Description |
|---|-------|------|------|-------------|
| W1 | Customizer loads globally | main plugin | 55 | `require_once ABSPATH . WPINC . '/class-wp-customize-control.php'` runs on ALL requests, not just Customizer. Performance hit on every page load. |
| W2 | Dead methods | admin class | 1300, 1634-1642 | `control_filter()` and `add_email_header()` are orphaned — never called. |
| W3 | Do-nothing alias | admin class | 1975-1977 | `wb_email_customizer_load_template_presets_cb()` is empty wrapper. |
| W4 | Duplicate hook registration | admin class | 126-136 | Anonymous closure on `customize_controls_enqueue_scripts` duplicates hook registration. |
| W5 | Duplicate site name | Modern/Minimal/Bold templates | Various | Site name appears twice in all non-default templates: once in the styled header and once as plain text in body. |
| W6 | Different email content per template | All templates | Various | Default shows "HTML Email Sub Heading" + Order #2020. Others show "Hi there. Your recent order..." + Order #1. Inconsistent preview data. |
| W7 | EDD item_id placeholder | main plugin | 139 | `'item_id' => 11` is a placeholder, not a real EDD download ID. |

### INFO (Nice to Fix)

| # | Issue | File | Description |
|---|-------|------|-------------|
| I1 | Dead classes (~600 lines) | Cache, Logger, Validator | Loaded but never instantiated. Can be removed. |
| I2 | ABSPATH guards missing | 5 include files | activator, deactivator, cache, logger, validator |
| I3 | README.txt boilerplate | README.txt | Still has "comments, spam" tags and "Requires at least: 3.0.1" |
| I4 | No .distignore | - | No distribution exclusion file |
| I5 | No gruntfile/build process | - | No automated zip build |
| I6 | Version 1.2.0 everywhere | headers | Version needs bump after fixes |
| I7 | Old edd-license/ dir | edd-license/ | Legacy EDD license files coexist with new vendor/edd-sl-sdk/ |

---

## Template Quality Assessment

### Which Template to Keep?

| Template | Quality | Issues | Recommendation |
|----------|---------|--------|----------------|
| **Default** | Best | Minor: "HTML Email Sub Heading" placeholder text | KEEP as primary |
| Modern | Good | Duplicate site name, different preview data | DISABLE (Pro feature later) |
| Minimal | Good | Duplicate site name, different preview data, minimal styling | DISABLE (Pro feature later) |
| Bold | Fair | Duplicate site name, pink background may not be popular | DISABLE (Pro feature later) |

**Recommendation:** Keep Default as the only active template. It's the closest to WooCommerce's native email look, has the fewest issues, and is most familiar to users. The other 3 templates can be gated as "Pro" features once they're polished.

---

## JS Architecture Summary

### Files That Exist
| File | Purpose | Enqueue Location |
|------|---------|------------------|
| `customizer-wbpreview.min.js` | Customizer live preview (handles settings → preview iframe communication) | Line 235 on `customize_controls_enqueue_scripts` |
| `customizer-control.min.js` | Customizer control panel (handles control interactions) | Line 238 on `customize_controls_enqueue_scripts` |
| `email-customizer-for-woocommerce-admin.min.js` | Admin page scripts + template preset AJAX | Line 240 on `customize_controls_enqueue_scripts` |

### Files That DON'T Exist (but are enqueued)
| File | Enqueue Location | Hook |
|------|------------------|------|
| `customizer.min.js` / `customizer.js` | Line 1656 `wb_email_customizer_enqueue_customizer_script()` | `customize_preview_init` |

### Fix for C1
The method `wb_email_customizer_enqueue_customizer_script()` (line 1647-1657) should either:
- **Option A:** Be removed entirely (the actual preview scripts are already loaded at lines 235-238)
- **Option B:** Changed to enqueue `customizer-wbpreview.min.js` instead of `customizer.min.js`

The working theory: the original developer renamed `customizer.js` to `customizer-wbpreview.js` but forgot to update this second enqueue method. Since the preview iframe's `customize_preview_init` hook fires AFTER `customize_controls_enqueue_scripts`, and the scripts are already loaded, Option A (remove the method) is likely correct.

---

## Action Plan (Prioritized)

### Phase 1: Critical Bug Fixes
1. Fix C1: Remove orphaned `wb_email_customizer_enqueue_customizer_script()` method and its hook registration
2. Fix C2: Move `run_email_customizer_for_woocommerce()` call inside WC dependency check
3. Fix C3: Add `isset()` guards for `$additional_content` in all 4 templates
4. Fix C4: Add `wp_unslash()` to `$_POST['settings']`
5. Fix C5: Don't mutate `$_GET` directly, use a local copy

### Phase 2: Cleanup
1. Remove dead code: Cache, Logger, Validator classes (~600 lines)
2. Remove dead methods: `control_filter()`, `add_email_header()`, `wb_email_customizer_load_template_presets_cb()`
3. Remove old `edd-license/` directory (replaced by `vendor/edd-sl-sdk/`)
4. Add ABSPATH guards to include files
5. Fix W1: Conditional load of `WP_Customize_Control` class

### Phase 3: Simplify to 1 Template
1. Keep Default template as the only active option
2. Add template-gating logic (templates 1-3 show "Pro" badge, non-selectable)
3. Fix duplicate site name in templates 1-3 (for future Pro use)
4. Normalize preview data across templates

### Phase 4: Release Prep
1. Rewrite README.txt
2. Add .distignore
3. Add gruntfile.js for build process
4. Version bump to 1.3.0
5. Update CLAUDE.md changelog

---

*Generated: 2026-02-25*
