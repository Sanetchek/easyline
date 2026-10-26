<?php
/**
 * Smoke tests for theme performance / crash-prevention fixes.
 */

declare(strict_types=1);

$themeRoot = dirname(__DIR__, 2);
$failed    = 0;

function perf_assert(bool $condition, string $message): void {
	global $failed;
	if ($condition) {
		echo "OK: {$message}\n";
		return;
	}
	fwrite(STDERR, "FAIL: {$message}\n");
	$failed++;
}

$productCat = (string) file_get_contents($themeRoot . '/taxonomy-product_cat.php');
perf_assert(strpos($productCat, "'posts_per_page'         => -1") === false, 'product_cat must not use posts_per_page => -1');
perf_assert(strpos($productCat, 'posts_per_page') !== false && strpos($productCat, '24') !== false, 'product_cat must paginate (~24)');
perf_assert(strpos($productCat, 'wc_get_product') !== false, 'product_cat must use wc_get_product');
perf_assert(strpos($productCat, 'paginate_links') !== false, 'product_cat must expose pagination');

$blog = (string) file_get_contents($themeRoot . '/template-blog.php');
perf_assert(strpos($blog, 'query_posts') === false, 'template-blog must not use query_posts');
perf_assert(strpos($blog, "'posts_per_page' => 12") !== false, 'template-blog must paginate (12 per page)');
perf_assert(strpos($blog, "'posts_per_page' => -1") === false, 'template-blog must not use posts_per_page => -1');
perf_assert(strpos($blog, 'the_content') === false, 'template-blog must not render full the_content in list');

$category = (string) file_get_contents($themeRoot . '/category.php');
perf_assert(strpos($category, "'posts_per_page' => -1") === false, 'category.php must not use posts_per_page => -1');

$recipes = (string) file_get_contents($themeRoot . '/template-blog_recipies_page.php');
perf_assert(strpos($recipes, "'posts_per_page' => -1") === false, 'recipes template must not use posts_per_page => -1');

$cart = (string) file_get_contents($themeRoot . '/template-parts/header/cart.php');
perf_assert(strpos($cart, 'easyline_has_woocommerce_session') !== false, 'header cart must be session-aware');

$woo = (string) file_get_contents($themeRoot . '/inc/woo.php');
perf_assert(strpos($woo, 'function easyline_has_woocommerce_session') !== false, 'woo.php must define session helper');
perf_assert(strpos($woo, 'add_foobar') === false, 'dead add_foobar endpoint must be removed');
perf_assert(strpos($woo, "check_ajax_referer('easyline_ajax'") !== false, 'AJAX handlers must verify nonce');
perf_assert(strpos($woo, 'easyline_ajax_rate_limit') !== false, 'zakaz must rate-limit');
perf_assert(strpos($woo, 'HTTP_HOST') === false, 'zakaz must not build From from HTTP_HOST');
perf_assert(strpos($woo, 'contact@easyline.co.il') !== false, 'zakaz must use fixed From domain');
perf_assert(strpos($woo, "/^[a-f0-9]{32}$/") !== false, 'set_quantity_custom must validate cart item key format');

$emails = (string) file_get_contents($themeRoot . '/inc/emails.php');
perf_assert(strpos($emails, "check_ajax_referer('easyline_ajax'") !== false, 'business_order must verify nonce');

$commonJs = (string) file_get_contents($themeRoot . '/js/common.js');
perf_assert(strpos($commonJs, '.load(location.href') === false, 'common.js must not full-page .load(location.href)');
perf_assert(strpos($commonJs, 'easylineApplyFragments') !== false, 'common.js must apply AJAX fragments');
perf_assert(strpos($commonJs, "typeof $.fn.apImageZoom") !== false, 'common.js must guard apImageZoom when zoom script is conditional');

$functions = (string) file_get_contents($themeRoot . '/functions.php');
perf_assert(strpos($functions, 'needs_zoom') !== false || strpos($functions, 'is_singular(\'product\')') !== false, 'zoom assets must be conditional');
perf_assert(strpos($functions, 'easylineAjax') !== false, 'functions.php must localize easylineAjax nonce');

$mhp = (string) file_get_contents($themeRoot . '/inc/muscle-health-performance.php');
if (preg_match('/function easyline_mhp_default_product_ids\(\)\s*\{([\s\S]*?)\n\}/', $mhp, $mhpFn)) {
	perf_assert(strpos($mhpFn[1], 'posts_per_page') === false, 'MHP default product IDs must not use posts_per_page scan');
	perf_assert(strpos($mhpFn[1], '$wpdb->prepare') !== false, 'MHP default product IDs must use targeted SQL lookup');
} else {
	perf_assert(false, 'MHP default product IDs function must exist');
}

$business = (string) file_get_contents($themeRoot . '/template-business_order_page.php');
perf_assert(strpos($business, 'easyline_israel_cities') !== false, 'business order cities query must be transient-cached');

exit($failed > 0 ? 1 : 0);
