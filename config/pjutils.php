<?php

return [

    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),

    'app_name' => env('APP_NAME', 'App'),

    'app_url' => env('APP_URL', 'http://localhost'),

    /**
     * Path to the logo - relative to the public directory
     * Set to null to disable the logo
     */
    'email_logo_path' => 'images/logo/logo.svg',

    'table' => [

        /**
         * Global default maximum character length for table cell values.
         * Cells exceeding this limit are truncated with an ellipsis and show the full value on hover.
         * Can be overridden per table via getDefaultMaxLength() and per cell via the maxLength parameter.
         * Set to null to disable truncation globally.
         */
        'default_max_length' => null,

    ],

];