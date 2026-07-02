<?php
/**
 * Theme smoke test runner.
 */

declare(strict_types=1);

$themeRoot = dirname(__DIR__, 2);
$required = [
    'SearchFormTest.php',
];

$failed = 0;

foreach ($required as $file) {
    $path = __DIR__ . '/' . $file;
    if (! is_readable($path)) {
        fwrite(STDERR, "Missing smoke test: {$file}\n");
        $failed++;
        continue;
    }

    passthru('php ' . escapeshellarg($path), $exitCode);
    if ($exitCode !== 0) {
        $failed++;
    }
}

exit($failed > 0 ? 1 : 0);
