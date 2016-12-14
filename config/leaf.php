<?php

return [
    'uri' => 'admin',
    'menu' => [
        [
            'controller' => \CubeSystems\Leaf\Http\Controllers\Admin\DashboardController::class,
            'visible' => false,
        ],
        [
            'title' => 'Nodes',
            'controller' => \CubeSystems\Leaf\Http\Controllers\Admin\NodeController::class,
        ],
        [
            'title' => 'Users',
            'items' => [
                [
                    'title' => 'Admin users',
                    'route' => 'admin.users.index',
                    'roles' => [ 'administrator' ]
                ],
                [
                    'title' => 'Admin roles',
                    'route' => 'admin.roles.index',
                    'roles' => [ 'administrator' ]
                ],
            ]
        ],
    ],
    'pagination' => [
        'items_per_page' => 15,
    ],
    'content_types' => [
        \CubeSystems\Leaf\Pages\TextPage::class,
    ],
];
