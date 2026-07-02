<?php
/**
 * Smoke test: search form routes queries to site root.
 */

declare(strict_types=1);

$themeRoot = dirname(__DIR__, 2);
$searchFormPath = $themeRoot . '/searchform.php';

if (! is_readable($searchFormPath)) {
    fwrite(STDERR, "Missing searchform.php\n");
    exit(1);
}

$contents = file_get_contents($searchFormPath);
if ($contents === false) {
    fwrite(STDERR, "Unable to read searchform.php\n");
    exit(1);
}

$failed = 0;

$assertContains = static function (string $needle, string $message) use ($contents, &$failed): void {
    if (strpos($contents, $needle) === false) {
        fwrite(STDERR, $message . "\n");
        $failed++;
    }
};

$assertNotContains = static function (string $needle, string $message) use ($contents, &$failed): void {
    if (strpos($contents, $needle) !== false) {
        fwrite(STDERR, $message . "\n");
        $failed++;
    }
};

$assertContains('method="get"', 'Search form must use GET method.');
$assertContains("home_url( '/' )", 'Search form action must target the site root.');
$assertContains('name="s"', 'Search input must keep the WordPress query var name "s".');
$assertNotContains('action="#"', 'Search form must not submit to the current page fragment.');
$assertNotContains('esc_attr_e(', 'Search form attributes must use esc_attr__ with echo wrappers.');

exit($failed > 0 ? 1 : 0);
