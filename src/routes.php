<?php

use Kirby\Filesystem\File;
use Kirby\Http\Response;
use Kirby\Uuid\FileUuid;

$kirby = kirby();

return [
    [
        'pattern' => 'file-content',
        'action' => function () use ($kirby) {
            if ($file = $kirby->request()->query()->toArray()['file']) {
                return Response::json([
                    'code' => (new File(FileUuid::for($file)->url()))->read(),
                ], 200);
            }
            return Response::json([
                'code' => null,
            ], 400);
        }
    ]
];
