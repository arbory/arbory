<?php

use CubeSystems\Leaf\Menu\AbstractItem;
use CubeSystems\Leaf\Services\Module;

return [
    'uri' => 'admin',
    'modules' => [
    ],
    'pagination' => [
        'items_per_page' => 15,
    ],
    'content_types' => [
        \CubeSystems\Leaf\Pages\TextPage::class,
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
    'locales' => [
        'en'
    ]
];
