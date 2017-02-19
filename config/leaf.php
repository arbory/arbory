<?php

use CubeSystems\Leaf\Menu\AbstractItem;
use CubeSystems\Leaf\Services\Module;

return [
    'uri' => 'admin',
    'menu' => [
        [
            'title' => 'Nodes',
            'type' => AbstractItem::TYPE_CRUD_MODULE,
            'module_name' => 'nodes',
        ],
        [
            'title' => 'Users',
            'type' => AbstractItem::TYPE_ITEM_GROUP,
            'items' => [
                [
                    'title' => 'Admin users',
                    'type' => AbstractItem::TYPE_CRUD_MODULE,
                    'module_name' => 'admin_users',
                ],
                [
                    'title' => 'Admin roles',
                    'type' => AbstractItem::TYPE_CRUD_MODULE,
                    'module_name' => 'admin_roles',
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
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\NodeCrudController::class,
        ],
        [
            'name' => 'admin_users',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\UserController::class,
            'authorization_type' => Module::AUTHORIZATION_TYPE_ROLES,
            'authorized_roles' => [ 'administrator' ],
        ],
        [
            'name' => 'admin_roles',
            'controller_class' => \CubeSystems\Leaf\Http\Controllers\Admin\RoleController::class,
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
