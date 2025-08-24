<?php
return [
    'is_config_override_enable' => env('CONFIG_OVERRIDE_ENABLED', false),
    'is_caching_enable' => env('CONFIG_OVERRIDE_CACHED', false),
    'cache_prefix' => env('CONFIG_OVERRIDE_PREFIX', 'config_override'),
];
