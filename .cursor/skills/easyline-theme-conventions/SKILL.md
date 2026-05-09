---
name: easyline-theme-conventions
description: >-
  Guides WordPress theme work for easyline2—file layout, i18n, escaping, RTL/WPML,
  and security. Use when editing this theme, adding templates, or when the user
  mentions easyline, easyline2, or theme structure.
disable-model-invocation: true
---

# easyline2 theme conventions

## Layout

- **Root**: `functions.php`, page templates (`template-*.php`), `header.php`, `footer.php`, `index.php`, singles, taxonomies.
- **`inc/`**: Shared PHP—`custom-code.php`, `woo.php`, `emails.php`, `embed-code.php`, `medical-post.php`, `blog-categories.php`. New theme logic belongs here and is loaded via `require_once` from `functions.php`.
- **`template-parts/`**: Reusable partials (e.g. header logo, menu, cart).
- **Assets**: `css/style.css` (main styles), `js/app.js`, `js/common.js`; some versions use `filemtime` in `functions.php`—keep that pattern for edited files.

## Internationalization

- Text domain: **`easyline`**.
- Translation files: `lang/` (`easyline.pot`, `he_IL.po`, `en_US.po`).
- After string changes: run `composer run make-pot` (from theme root) to refresh `lang/easyline.pot`, then update `.po` files as needed.

## Output and forms

- Escape output: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` as appropriate.
- Admin/settings forms: use `wp_nonce_field` / `wp_verify_nonce` and capability checks (`manage_options`, etc.), same pattern as WhatsApp settings in `functions.php`.

## RTL and WPML

- Keep `language_attributes()` in `<html>`, `body_class()` with RTL/LTR class (`is_rtl()`), and `do_action('wpml_add_language_selector')` in the header unless the user asks to change multilingual behavior.
- Test layout implications for RTL when changing header or grid markup.

## Security and portability

- Do **not** commit API keys, secrets, or passwords. Use options, `wp-config.php` constants, or filters; Google Maps and similar keys must not be hardcoded in tracked files.
- Hardcoded page IDs (e.g. `get_page_id_med_products()`) are fragile across environments—prefer ACF options or a configurable setting when touching that code.

## Quality commands (theme root)

- `composer run lint:php` — syntax check.
- `composer run lint:wpcs` — PHPCS (Theme Review ruleset via Composer dev deps).
