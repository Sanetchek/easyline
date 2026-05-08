# Plugin Deprecation Mitigation (Staging)

This project has a large volume of PHP `Deprecated` and textdomain timing notices in `wp-content/debug.log` coming from third-party plugins, not from theme code.

## Priority Plugins To Update

- `sitepress-multilingual-cms` (`4.9.2.1`)
- `wpml-string-translation` (`3.5.1`)
- `wp-rocket` (`3.11.0.3`)
- `woocommerce-google-dynamic-retargeting-tag` (`1.8.2`)

## Staging Update Cycle

1. Create a full database + files backup.
2. Update WPML stack first (core + addons in one cycle).
3. Clear caches and test:
   - FAQ page
   - single product page
   - cart/checkout
4. Update WP Rocket, clear all caches, re-test pages.
5. Update remaining plugins with deprecation noise.
6. Re-check `wp-content/debug.log` after each cycle.

## Notes

- WP-CLI is not available in this environment (`wp: command not found`), so plugin updates must be run from WordPress Admin or hosting tooling.
- Do not patch vendor/plugin source directly unless there is no maintained release with PHP 8.2+ compatibility.
