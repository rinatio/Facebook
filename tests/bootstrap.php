<?php
/**
 * Define your test application ID and secret in bootstrap-local.php file.
 * Like this:
 * define('FB_TEST_APP_ID', '12345');
 * define('FB_TEST_APP_SECRET', 'secret');
 */
if (!file_exists(__DIR__ . '/bootstrap-local.php')) {
    die('Please configure FB_TEST_APP within bootstrap-local.php');
}
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap-local.php';
