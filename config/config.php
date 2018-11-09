<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Settings Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default settings store that gets used while
    | using this settings library.
    |
    | Supported: "json", "database", "memory" (useful for testing)
    |
    */
    'store' => 'json',

    /*
    |--------------------------------------------------------------------------
    | JSON Store
    |--------------------------------------------------------------------------
    |
    | If the store is set to "json", settings are stored in the defined
    | file path in JSON format. Use full path to file.
    |
    */
    'path' => storage_path() . '/settings.json',

    /*
    |--------------------------------------------------------------------------
    | Database Store
    |--------------------------------------------------------------------------
    |
    | The settings are stored in the defined file path in JSON format.
    | Use full path to JSON file.
    |
    */

    // If set to null, the default connection will be used.
    'connection' => null,

    // Name of the table used.
    'table' => 'settings',
];
