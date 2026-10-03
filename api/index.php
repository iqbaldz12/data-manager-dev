<?php

// Fix working directory for Vercel (files are at /var/task)
$projectRoot = dirname(__DIR__);

// Bootstrap storage dirs that must exist at runtime (Vercel has /tmp writable)
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/app/livewire-tmp',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// Point Laravel's storage path to /tmp (only writable dir on Vercel)
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['APP_STORAGE_PATH'] = '/tmp/storage';

// Set the document root so Laravel can find public assets
$_SERVER['DOCUMENT_ROOT'] = $projectRoot . '/public';
chdir($projectRoot);

// Serve static files directly when accessed via the PHP runtime
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
$filePath = $projectRoot . '/public' . $path;

if ($path !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false; // Let the web server serve the file
}

require $projectRoot . '/public/index.php';