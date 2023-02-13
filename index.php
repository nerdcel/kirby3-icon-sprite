<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('nerdcel/icon-sprite', [
    'options' => require __DIR__ . '/src/config.php',

    'blueprints' => [
        'blocks/icon' => __DIR__ . '/blueprints/blocks/icon.yml',
        'files/svgicon' => __DIR__ . '/blueprints/files/svgicon.yml',
        'sections/svgsprite' => __DIR__ . '/blueprints/sections/svgsprite.yml',
        'tabs/icons' => __DIR__ . '/blueprints/tabs/icons.yml',
    ],

    'api' => [
        'routes' => require 'src/routes.php'
    ],

    'snippets' => [
        'blocks/icon' => __DIR__ . '/snippets/blocks/icon.php',
    ],
]);
