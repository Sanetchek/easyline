---
name: easyline-woocommerce-overrides
description: >-
  Guides WooCommerce template overrides and hooks for easyline2—woocommerce/ copies,
  inc/woo.php fragments, and regression checks. Use when editing cart, checkout,
  emails, mini-cart, or WooCommerce-related PHP in this theme.
disable-model-invocation: true
---

# easyline2 WooCommerce overrides

## Where changes go

- **Template overrides**: only under `woocommerce/` mirroring WooCommerce template paths (e.g. `cart/`, `checkout/`, `emails/`, `global/`).
- After WooCommerce upgrades, compare theme copies with WooCommerce defaults and update if templates changed upstream.

## Hooks over core edits

- Prefer `add_action` / `add_filter` in `inc/woo.php` (or a dedicated `inc/` file required from `functions.php`) instead of editing plugin files.

## Cart fragments (do not break selectors)

`inc/woo.php` registers `woocommerce_add_to_cart_fragments` output for:

- `#header-cart-count` — cart item count markup
- `#updated_header_mini_cart` — mini cart wrapper

If you change header or mini-cart HTML/CSS IDs, update the fragment keys in the filter to match, or cart AJAX updates will desync.

## Session / shipping

- `set_default_chosen_shipping_method` runs on `woocommerce_before_cart` and sets free shipping when available—be careful not to double-book conflicting shipping logic.

## Regression checklist

After cart, checkout, or fragment changes, verify:

- Cart page and mini-cart in header
- Checkout (billing/shipping/review as customized)
- Single product add-to-cart (simple products use custom helpers in `inc/custom-code.php`)

For broader staging/plugin context, see `docs/plugin-deprecation-mitigation.md`.
