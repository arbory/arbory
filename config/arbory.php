<?php

return [
    'uri' => 'admin',

    'locales' => [
        'en'
    ],

    'menu' => [
        Arbory\Base\Http\Controllers\Admin\NodesController::class,
        Arbory\Base\Http\Controllers\Admin\LanguageController::class,
        Arbory\Base\Http\Controllers\Admin\SettingsController::class,
        Arbory\Base\Http\Controllers\Admin\TranslationsController::class,
        Arbory\Base\Http\Controllers\Admin\RedirectsController::class,
        [
            Arbory\Base\Http\Controllers\Admin\UsersController::class,
            Arbory\Base\Http\Controllers\Admin\RolesController::class
        ],
    ],

    'pagination' => [
        'items_per_page' => 15,
    ],

    'fields' => [
        'map_coordinates' => [
            'zoom' => 12,
            'coordinates' => [
                'lat' => 56.94725473000847,
                'lng' => 24.099142639160167,
            ]
        ],
        'sprite_icon' => [
            'path' => base_path( 'resources/assets/svg/icons.svg' ),
        ],
    ],

    'auth' => [
        'activations' => [
            'expires' => 259200,
            'lottery' => [ 2, 100 ],
        ],
        'reminders' => [
            'expires' => 14400,
            'lottery' => [ 2, 100 ],
        ],
        'throttling' => [
            'global' => [
                'interval' => 900,
                'thresholds' => [
                    10 => 1,
                    20 => 2,
                    30 => 4,
                    40 => 8,
                    50 => 16,
                    60 => 12
                ],
            ],
            'ip' => [
                'interval' => 900,
                'thresholds' => 5,
            ],
            'user' => [
                'interval' => 900,
                'thresholds' => 5,
            ],
        ],
    ],
];
