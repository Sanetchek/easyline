---
name: easyline-staging-maintenance
description: >-
  Staging workflow for easyline2 sites—plugin updates, deprecation noise in debug.log,
  and cache/testing order. Use when updating WPML, WP Rocket, WooCommerce addons, or
  triaging PHP deprecation notices on staging.
disable-model-invocation: true
---

# easyline2 staging maintenance

## Source of truth

Read and follow **[docs/plugin-deprecation-mitigation.md](../../../docs/plugin-deprecation-mitigation.md)** (from theme root: `docs/plugin-deprecation-mitigation.md`) for:

- Which plugins tend to emit deprecations
- Recommended update order and versions to track
- Environment notes (e.g. WP-CLI availability)

Do not duplicate long plugin version lists in chat—point maintainers to that file and update the doc when priorities change.

## Short checklist

1. Full backup (database + files) before any plugin batch.
2. Update WPML stack as a single coordinated step when applicable.
3. Clear caches (including WP Rocket) after updates.
4. Smoke-test: FAQ, single product, cart, checkout (aligns with project doc).
5. Re-check `wp-content/debug.log` after each cycle.

## Rules

- Do not patch third-party plugin or `vendor` source unless there is no maintained release and the team explicitly accepts the maintenance cost.
