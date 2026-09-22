<?php
/**
 * Smoke test: Muscle Health & Performance template wiring.
 */

declare(strict_types=1);

$themeRoot = dirname(__DIR__, 2);
$failed = 0;

$assertTrue = static function (bool $condition, string $message) use (&$failed): void {
	if (!$condition) {
		fwrite(STDERR, $message . "\n");
		$failed++;
	}
};

$assertContains = static function (string $haystack, string $needle, string $message) use (&$failed): void {
	if (strpos($haystack, $needle) === false) {
		fwrite(STDERR, $message . "\n");
		$failed++;
	}
};

$requiredFiles = array(
	'template-muscle-health-performance.php',
	'inc/muscle-health-performance.php',
	'css/pages/muscle-health-performance.css',
	'img/muscle-health-performance-hero.webp',
	'template-parts/pages/muscle-health-performance/content.php',
	'template-parts/pages/muscle-health-performance/section-hero.php',
	'template-parts/pages/muscle-health-performance/section-why.php',
	'template-parts/pages/muscle-health-performance/section-science.php',
	'template-parts/pages/muscle-health-performance/section-audience.php',
	'template-parts/pages/muscle-health-performance/section-solutions.php',
	'template-parts/pages/muscle-health-performance/section-quality.php',
	'template-parts/pages/muscle-health-performance/section-knowledge.php',
	'template-parts/pages/muscle-health-performance/section-faq.php',
);

foreach ($requiredFiles as $relative) {
	$path = $themeRoot . '/' . $relative;
	$assertTrue(is_readable($path), "Missing required file: {$relative}");
}

$functions = (string) file_get_contents($themeRoot . '/functions.php');
$assertContains($functions, "inc/muscle-health-performance.php", 'functions.php must require muscle-health-performance module.');
$assertContains($functions, "template-muscle-health-performance.php", 'functions.php must enqueue assets for the muscle health template.');
$assertContains($functions, 'muscle-health-performance.css', 'functions.php must enqueue muscle-health-performance.css.');

$template = (string) file_get_contents($themeRoot . '/template-muscle-health-performance.php');
$assertContains($template, 'Template Name: Muscle Health & Performance', 'Template must declare Template Name header.');
$assertContains($template, "get_template_part('template-parts/pages/muscle-health-performance/content')", 'Template must load content partial.');
$assertContains($template, 'get_sidebar()', 'Template must keep theme sidebar shell.');

$acf = (string) file_get_contents($themeRoot . '/inc/muscle-health-performance.php');
$assertContains($acf, 'group_easyline_muscle_health_performance', 'ACF group key must be registered.');
$assertContains($acf, "add_action('acf/init', 'easyline_mhp_register_acf_fields')", 'ACF fields must register on acf/init.');
$assertContains($acf, "page_template", 'ACF location must target page template.');
$assertContains($acf, 'template-muscle-health-performance.php', 'ACF location must match template file.');
$assertContains($acf, "'type'          => 'post_object'", 'Solution cards must select one WooCommerce product.');
$assertContains($acf, "'type'         => 'repeater'", 'Solutions must use a repeater instead of a relationship field.');
$assertContains($acf, 'field_easyline_mhp_solutions_items', 'Solutions must use a clean repeater field separate from the legacy relationship value.');
$assertContains($acf, 'field_easyline_mhp_solutions_features', 'Each solution card must expose a nested feature repeater.');
$assertContains($acf, 'field_easyline_mhp_solutions_feature_text', 'Each product feature must expose an editable text field.');
$assertContains($acf, "array('product')", 'Solution product selectors must target WooCommerce products.');
$assertContains($acf, "array('post')", 'Knowledge relationship must target posts.');
$assertContains($acf, "field_easyline_mhp_' . \$key_base . '_tab", 'Section tabs must use stable field_easyline_mhp_*_tab keys.');
$assertContains($acf, "easyline_mhp_section_tab_fields('hero'", 'Hero tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('why'", 'Why tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('science'", 'Science tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('audience'", 'Audience tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('solutions'", 'Solutions tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('quality'", 'Quality tab must be registered.');
$assertContains($acf, "easyline_mhp_section_tab_fields('knowledge'", 'Knowledge tab must be registered.');
$assertContains($acf, "'name'          => 'mhp_knowledge_posts'", 'Knowledge posts relationship field must be registered.');
$assertTrue(
	(bool) preg_match(
		"/'name'\\s*=>\\s*'mhp_knowledge_posts'[\\s\\S]{0,400}?'max'\\s*=>\\s*0,/",
		$acf
	),
	'Knowledge posts relationship must have no max selection limit (max => 0).'
);
$assertContains($acf, "easyline_mhp_section_tab_fields('faq'", 'FAQ tab must be registered.');
$assertContains($acf, "'type'  => 'tab'", 'ACF tab field type must be registered.');

$tabSections = array('hero', 'why', 'science', 'audience', 'solutions', 'quality', 'knowledge', 'faq');
foreach ($tabSections as $section) {
	$assertTrue(
		substr_count($acf, "easyline_mhp_section_tab_fields('{$section}'") === 1,
		"Section tab '{$section}' must be registered exactly once."
	);
}

$helpers = array(
	'function easyline_get_acf_image_url',
	'function easyline_mhp_normalize_rows',
	'function easyline_mhp_section_enabled',
	'function easyline_mhp_resolve_link',
	'function easyline_mhp_product_feature_lines',
	'function easyline_mhp_product_short_name',
	'function easyline_mhp_default_solution_rows',
	'function easyline_mhp_get_icon_svg',
	'function easyline_mhp_default_content',
	'function easyline_mhp_seed_page_defaults',
	'function easyline_mhp_get_field',
);
foreach ($helpers as $helper) {
	$assertContains($acf, $helper, "Missing helper: {$helper}");
}
$assertContains($acf, 'EasyLine Muscle Health & Performance', 'Starter content must include the design headline.');
$assertContains($acf, 'המדע שמאחורי בריאות השריר', 'Starter content must include the science heading.');
$assertContains($acf, "'title' => 'Whey Protein'", 'Starter content must include the Whey Protein card.');
$assertContains($acf, "'title' => 'Leucine'", 'Starter content must include the Leucine card.');
$assertContains($acf, "'title' => 'BCAA'", 'Starter content must include the BCAA card.');
$assertContains($acf, "'title' => 'HMB'", 'Starter content must include the HMB card.');
$assertContains($acf, "'name' => 'GMP'", 'Starter content must include the GMP certification.');
$assertContains($acf, "'name' => 'HACCP'", 'Starter content must include the HACCP certification.');
$assertContains($acf, 'ISO 9001:2015', 'Starter content must include the ISO certification.');
$assertContains($acf, "'icon_key' => 'scoop'", 'Starter science content must include built-in icons.');
$assertContains($acf, 'field_easyline_mhp_hero_image', 'Hero must expose an editable image field.');
$assertContains($acf, 'field_easyline_mhp_quality_logo_name', 'Quality logos must support a text badge fallback.');
$assertContains($acf, "add_action('save_post_page', 'easyline_mhp_seed_page_on_save'", 'Starter values must seed when the page is saved.');
$assertContains($acf, "add_action('admin_init', 'easyline_mhp_seed_existing_pages')", 'Existing template pages must receive starter values.');

$faqSection = (string) file_get_contents($themeRoot . '/template-parts/pages/muscle-health-performance/section-faq.php');
$assertContains($faqSection, 'aria-expanded', 'FAQ questions must expose aria-expanded.');
$assertContains($faqSection, 'aria-controls', 'FAQ questions must expose aria-controls.');
$assertContains($faqSection, 'esc_html($question)', 'FAQ questions must escape plain text.');
$assertContains($faqSection, 'wp_kses_post($answer)', 'FAQ answers must use wp_kses_post.');

$hero = (string) file_get_contents($themeRoot . '/template-parts/pages/muscle-health-performance/section-hero.php');
$assertContains($hero, 'esc_url($cta[\'url\'])', 'Hero CTA URL must be escaped.');
$assertContains($hero, 'esc_html($title)', 'Hero title must be escaped.');
$assertContains($hero, 'mhp_hero_image', 'Hero must render the configured ACF image.');
$assertContains($hero, 'muscle-health-performance-hero.webp', 'Hero must include the reference image fallback.');

$solutions = (string) file_get_contents($themeRoot . '/template-parts/pages/muscle-health-performance/section-solutions.php');
$assertContains($solutions, 'wc_get_product', 'Solutions section must load WooCommerce products.');
$assertContains($solutions, 'esc_url($permalink)', 'Product permalinks must be escaped.');
$assertContains($solutions, "\$card['features']", 'Solutions section must render features from the nested repeater.');
$assertContains($solutions, "_x('%1\$s %2\$s'", 'Product CTA must include the selected product name.');

$knowledge = (string) file_get_contents($themeRoot . '/template-parts/pages/muscle-health-performance/section-knowledge.php');
$assertContains($knowledge, 'esc_html(get_the_title($article_id))', 'Article titles must be escaped.');
$assertContains($knowledge, 'get_the_post_thumbnail', 'Knowledge cards must render selected post featured images.');

$commonJs = (string) file_get_contents($themeRoot . '/js/common.js');
$assertContains($commonJs, 'aria-expanded', 'common.js FAQ handler must manage aria-expanded.');

$science = (string) file_get_contents($themeRoot . '/template-parts/pages/muscle-health-performance/section-science.php');
$assertContains($science, 'mhp-science__plus', 'Science cards must render the static plus icon.');
$assertNotContains = function (string $haystack, string $needle, string $message) use (&$failed): void {
	if (strpos($haystack, $needle) !== false) {
		fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL);
		$failed++;
		return;
	}
	echo 'PASS: ' . $message . PHP_EOL;
};
$assertNotContains($science, 'mhp-science__toggle', 'Science cards must not render a toggle button.');

$css = (string) file_get_contents($themeRoot . '/css/pages/muscle-health-performance.css');
$assertContains($css, '--mhp-green-dark', 'Page CSS must define theme color tokens.');
$assertContains($css, '@media (max-width: 767px)', 'Page CSS must include mobile breakpoints.');
$assertContains($css, 'prefers-reduced-motion', 'Page CSS must respect reduced motion.');

exit($failed > 0 ? 1 : 0);
