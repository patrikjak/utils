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

];