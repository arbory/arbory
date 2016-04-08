<?php

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Nodes',
            'controller' => \CubeSystems\Leaf\Http\Controllers\Admin\NodesController::class,
        ],
        [
            'title' => 'Users',
            'controller' => \CubeSystems\Leaf\Http\Controllers\Admin\UsersController::class,
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
    'content_types' => [
        \CubeSystems\Leaf\Pages\TextPage::class,
    ],
];
