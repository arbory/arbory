<?php

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Users',
            'controller' => \CubeSystems\Leaf\Http\Controllers\UsersController::class,
        ],
    ],
    'pagination' => [
        'items_per_page' => 15,
    ],
    'field_types' => [
        'belongsTo' => \CubeSystems\Leaf\Fields\BelongsTo::class,
        'richtext' => \CubeSystems\Leaf\Fields\Richtext::class,
        'text' => \CubeSystems\Leaf\Fields\Text::class,
        'toolbox' => \CubeSystems\Leaf\Fields\Toolbox::class,
    ],
];
