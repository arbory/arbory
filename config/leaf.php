<?php

use CubeSystems\Leaf\Menu\AbstractItem;
use CubeSystems\Leaf\Services\Module;

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Nodes',
            'module_name' => 'nodes',
            'route_name' => 'admin.nodes.index'
        ],
        [
            'title' => 'Users',
            'items' => [
                [
                    'title' => 'Admin users',
                    'module_name' => 'admin_users',
                    'route_name' => 'admin.users.index'
                ],
                [
                    'title' => 'Admin roles',
                    'module_name' => 'admin_roles',
                    'route_name' => 'admin.roles.index'
                ],
            ]
        ],
        [
            'title' => 'Translations',
            'module_name' => 'translations',
            'route_name' => 'admin.translations.index',
        ],
    ],
    'modules' => [
        [
            'name' => 'dashboard',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\DashboardController::class,
        ],
        [
            'name' => 'nodes',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\NodesController::class,
        ],
        [
            'name' => 'admin_users',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\UsersController::class,
            'authorization_type' => Module::AUTHORIZATION_TYPE_ROLES,
            'authorized_roles' => [ 'administrator' ],
        ],
        [
            'name' => 'admin_roles',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\RolesController::class,
            'authorization_type' => Module::AUTHORIZATION_TYPE_ROLES,
            'authorized_roles' => [ 'administrator' ],
        ],
        [
            'name' => 'translations',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\TranslationsController::class,
            'authorization_type' => Module::AUTHORIZATION_TYPE_ROLES,
            'authorized_roles' => [ 'administrator' ],
        ],

    ],
    'pagination' => [
        'items_per_page' => 15,
    ],
    'content_types' => [
        \CubeSystems\Leaf\Pages\TextPage::class,
    ],
];
