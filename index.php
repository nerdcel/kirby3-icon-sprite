<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('nerdcel/icon-sprite', [
    'options' => require __DIR__ . '/src/config.php',

    'blueprints' => [
        'files/svgicon' => __DIR__ . '/blueprints/files/svgicon.yml',
        'sections/svgsprite' => __DIR__ . '/blueprints/sections/svgsprite.yml',
    ]
]);
