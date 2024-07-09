<?php 

return [
    'woke-up-chose-violence' => [
        'name'          => 'woke-up-chose-violence',
        'label'         => 'woke-up-chose-violence::menu.main_level',
        'icon'          => 'fas fa-user-secret',
        'plural'        => false,
        'permission'    => 'woke-up-chose-violence.view',
        'route_segment' => 'woke-up-chose-violence',
        'entries'       => [
            [
                'name'              => 'Character Map',
                'label'             => 'woke-up-chose-violence::menu.character_map',
                'icon'              => 'fas fa-map-marker',
                'plural'            => false,
                'permission'        => 'woke-up-chose-violence.view', // Everyone can see this, the controller will filter appropriately, itself.
                'route'             => 'woke-up-chose-violence.home',
            ],
        ]
    ],
];
