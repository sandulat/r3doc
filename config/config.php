<?php

return [

    /*
    |--------------------------------------------------------------------------
    | R3doc Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where R3doc will be accessible from.
    |
    */
    'path' => '/docs',

    /*
    |--------------------------------------------------------------------------
    | Blacklisted routes
    |--------------------------------------------------------------------------
    |
    | Here you may put the routes that should be excluded from documentation
    | generation. Wildcards are supported.
    |
    */
    'blacklistRoutes' => [
        'telescope/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Whitelisted routes
    |--------------------------------------------------------------------------
    |
    | Here you may put the only routes that should be included in
    | documentation generation. Wildcards are supported.
    |
    */
    'whitelistRoutes' => [
        'api/*',
    ],
];
