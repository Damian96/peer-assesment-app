<?php

require "./vendor/autoload.php";

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('./app/');

$options = [
    'theme'                => 'default',
    'title'                => 'Peer Assessment App Documentation',
    'build_dir'            => __DIR__ . '/docs/',
    'cache_dir'            => __DIR__ . '/docs/cache/',
];

return new Sami($iterator, $options);
