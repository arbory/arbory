<?php

use CubeSystems\Leaf\Menu\AbstractItem;
use CubeSystems\Leaf\Services\Module;

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Nodes',
            'type' => AbstractItem::TYPE_MODULE,
            'module_name' => 'nodes',
            'route_name' => 'admin.nodes.index'
        ],
        [
            'title' => 'Users',
            'type' => AbstractItem::TYPE_ITEM_GROUP,
            'items' => [
                [
                    'title' => 'Admin users',
                    'type' => AbstractItem::TYPE_MODULE,
                    'module_name' => 'admin_users',
                    'route_name' => 'admin.users.index'
                ],
                [
                    'title' => 'Admin roles',
                    'type' => AbstractItem::TYPE_MODULE,
                    'module_name' => 'admin_roles',
                    'route_name' => 'admin.roles.index'
                ],
            ]
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
