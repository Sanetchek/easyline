<?php
/**
 * Smoke test: excerpt sanitizer strips legacy language markers and boilerplate.
 */

declare(strict_types=1);

/**
 * Mirror of easyline_sanitize_post_list_excerpt_text() for offline assertions.
 */
function easyline_sanitize_post_list_excerpt_text_mirror(string $text): string
{
    $text = preg_replace('/\[:[a-z]{2}\]/iu', '', $text);
    $text = preg_replace('/\[:\]/iu', '', $text);
    $text = preg_replace('/\[[a-z]{2}:\]/iu', '', $text);
    $text = preg_replace('/\s*\[\.\.\.\]\s*/u', ' ', $text);
    $text = str_replace(array('[...]', '[…]'), '', $text);
    $text = preg_replace('/\s*(\.{3,}|…)\s*$/u', '', $text);
    $text = preg_replace('/\s{2,}/u', ' ', $text);
    $text = trim($text);

    if ($text !== '' && preg_match('/Welcome to WordPress\. This is your first post/i', $text)) {
        return '';
    }

    return $text;
}

$sanitize = 'easyline_sanitize_post_list_excerpt_text_mirror';
$failed = 0;

$assertSame = static function (string $expected, string $actual, string $message) use (&$failed): void {
    if ($expected !== $actual) {
        fwrite(STDERR, $message . "\n");
        fwrite(STDERR, "  expected: {$expected}\n");
        fwrite(STDERR, "  actual:   {$actual}\n");
        $failed++;
    }
};

$helloWorld = '[:en]Welcome to WordPress. This is your first post. Edit or delete it, then start writing!';
$assertSame('', $sanitize($helloWorld), 'Default Hello World boilerplate should return empty excerpt.');

$wpmlMarker = 'טקסט בעברית[en:]More English';
$assertSame('טקסט בעבריתMore English', $sanitize($wpmlMarker), 'WPML [en:] markers should be stripped.');

$hebrewExcerpt = 'מחקר Prime - תכנית נסיינים - ברוכים הבאים!';
$assertSame($hebrewExcerpt, $sanitize($hebrewExcerpt), 'Normal Hebrew excerpt text should be preserved.');

$qtranslateBlock = '[:he]טקסט בעברית[:en]English text[:]';
$assertSame('טקסט בעבריתEnglish text', $sanitize($qtranslateBlock), 'qTranslate markers should be stripped.');

$themeRoot = dirname(__DIR__, 2);
$blogCategoriesPath = $themeRoot . '/inc/blog-categories.php';
if (! is_readable($blogCategoriesPath)) {
    fwrite(STDERR, "Missing inc/blog-categories.php\n");
    exit(1);
}

$blogCategories = file_get_contents($blogCategoriesPath);
if ($blogCategories === false || strpos($blogCategories, 'easyline_sanitize_post_list_excerpt_text') === false) {
    fwrite(STDERR, "blog-categories.php must define easyline_sanitize_post_list_excerpt_text().\n");
    exit(1);
}

$searchPath = $themeRoot . '/search.php';
if (! is_readable($searchPath)) {
    fwrite(STDERR, "Missing search.php\n");
    exit(1);
}

$searchTemplate = file_get_contents($searchPath);
if ($searchTemplate === false) {
    fwrite(STDERR, "Unable to read search.php\n");
    exit(1);
}

if (strpos($searchTemplate, 'easyline_get_post_list_excerpt_plain') === false) {
    fwrite(STDERR, "search.php must use easyline_get_post_list_excerpt_plain().\n");
    $failed++;
}

if (strpos($searchTemplate, 'the_excerpt()') !== false) {
    fwrite(STDERR, "search.php must not call the_excerpt() for result snippets.\n");
    $failed++;
}

exit($failed > 0 ? 1 : 0);
