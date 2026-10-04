<?php

if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

$projectRoot = dirname(__DIR__);

// ── 1. Bootstrap writable dirs on Vercel (/tmp is the only writable location) ──
$tmpDirs = [
    '/tmp/bootstrap/cache',
    '/tmp/storage/app/public',
    '/tmp/storage/app/livewire-tmp',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/views',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// ── 2. Copy bootstrap/cache precompiled files if not yet in /tmp ──
$cacheFiles = ['packages.php', 'services.php'];
foreach ($cacheFiles as $cacheFile) {
    $src = $projectRoot . '/bootstrap/cache/' . $cacheFile;
    $dst = '/tmp/bootstrap/cache/' . $cacheFile;
    if (file_exists($src) && !file_exists($dst)) {
        copy($src, $dst);
    }
}

// ── 3. Tell Laravel where to find storage & bootstrap/cache ──
$_ENV['APP_STORAGE_PATH']        = '/tmp/storage';
$_SERVER['APP_STORAGE_PATH']     = '/tmp/storage';
$_ENV['APP_BOOTSTRAP_PATH']      = '/tmp/bootstrap';
$_SERVER['APP_BOOTSTRAP_PATH']   = '/tmp/bootstrap';

// ── 4. Ensure Blade compiled views go to /tmp ──
$_ENV['VIEW_COMPILED_PATH']      = '/tmp/views';
$_SERVER['VIEW_COMPILED_PATH']   = '/tmp/views';

// ── 5. Fix working directory & HTTPS ──
chdir($projectRoot);
$_SERVER['DOCUMENT_ROOT'] = $projectRoot . '/public';
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

// ── 6. Pass through to Laravel ──
require $projectRoot . '/public/index.php';