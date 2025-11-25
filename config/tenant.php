<?php

return [
    'model' => \App\Models\Tenant::class,

    'middleware' => [
        'set' => \App\Http\Middleware\SetTenant::class,
        'ensure' => \App\Http\Middleware\EnsureTenantSelected::class,
    ],

    'cache' => [
        'enabled' => env('TENANT_CACHE_ENABLED', true),
        'ttl' => env('TENANT_CACHE_TTL', 3600),
    ],
];
