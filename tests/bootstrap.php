<?php
if (!file_exists(__DIR__ . '/bootstrap-local.php')) {
    die('Please configure FB_TEST_APP within bootstrap-local.php');
}
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap-local.php';
