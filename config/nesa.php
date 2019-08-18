<?php

/**
 *
 * Define default configs for the app itself
 *
 */
return [
    'default_node_version' => env('NESA_DEFAULT_NODE_VERSION', '10.16.1'),
    'default_package_manager' => env('NESA_DEFAULT_PACKAGE_MANAGER', 'npm'),
    'default_start_command' => env('NPM_START_COMMAND_CONFIG', 'start'),
    'ip' => env('NESA_IP_ADDRESS'),
    'metrics_port' => env('NESA_METRICS_PORT', 33628)
];
