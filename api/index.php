<?php

// Vercel Serverless Database Fallback
// Prevent 30-second PDO connection timeout when DB_HOST is set to localhost (127.0.0.1) on Vercel
$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1');

if (($dbHost === '127.0.0.1' || $dbHost === 'localhost') && !getenv('DB_URL')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=/tmp/database.sqlite');
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
    $_SERVER['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';

    if (!file_exists('/tmp/database.sqlite') || filesize('/tmp/database.sqlite') === 0) {
        @touch('/tmp/database.sqlite');
        try {
            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
            $kernel->call('migrate', ['--force' => true]);
            $kernel->call('db:seed', ['--force' => true]);
        } catch (\Throwable $e) {
            // Ignore if migration error during initial bootstrap
        }
    }
}

require __DIR__ . '/../public/index.php';