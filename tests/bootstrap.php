<?php

// project base dir
$basedir = dirname(__DIR__);

// autoload search paths
$search = [
    dirname($basedir) . '/autoload.php',
    $basedir.'/vendor/autoload.php',
];

// autoload
foreach ($search as $auto) {
    if (file_exists($auto)) {
        $loader = require $auto;
        break;
    }
}
