<?php

return [
    // Database information.
    'db'=> [
        'host'  => 'localhost', // Host
        'user'  => 'root',      // Username
        'pass'  => '',          // Password
        'db'    => 'ctbans', // Database name
        'port'  => 3600,        // Database port
        'charset'=>'utf8',      // NAMES charset, CHANGE ONLY IF KNOW WHAT YOU'RE DOING!!+++++
    ],

    'site' => [
        // url => full website path.
        'url'   => 'https://ctbans.localhost',
        // Website name
        'name'  => 'This Clan',
        // Logo
        'logo'  => 'https://i.imgur.com/OE4L9rk.png',
        // Links to show in the menu dropdown :)
        'links' => [
            // NAME to show (the clickable part) => web url
            'ban'  => 'https://website.com/bans',
            'HLStatsX'  => 'https://website.com/stats',
            // ''  => '', // Empty labels mean show a list separator
        ]
    ],

    'steam' => [
        'apikey'    => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    ],

    'debug' => false,

    // Allowed steam 64 (community) ids to login to admin.
    'allowedSteamids'   => [
        '76561198062770062',
    ],
];