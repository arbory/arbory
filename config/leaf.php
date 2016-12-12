<?php

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Nodes',
            'controller' => \CubeSystems\Leaf\Http\Controllers\Admin\NodeController::class,
        ],
        [
            'title' => 'Users',
            'items' =>[
                [
                    'title' => 'Admin users',
                    'route' => 'admin.users.index',
                    'roles' => [ 'users_admin' ]
                ],
                [
                    'title' => 'Admin roles',
                    'route' => 'admin.roles.index',
                    'roles' => [ 'roles_admin' ]
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
