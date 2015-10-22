<?php

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'users',
            'icon' => 'user',
            'route' => 'admin.users.index',
        ],
        [
            'title' => 'permissions',
            'icon' => 'user',
            'route' => 'admin.users.index',
        ],
        [
            'title' => 'shits',
            'icon' => 'globe',
            'items' => [
                'admin.users.index',
                'admin.users.index'
            ]
        ],
    ],
];
