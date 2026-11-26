<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Consent cookies
    |--------------------------------------------------------------------------
    |
    | "all" = analytics / marketing granted (Accept).
    | "essential" = non-essential rejected (Reject).
    |
    */

    'cookies' => [
        'all' => 'cookies_all',
        'essential' => 'cookies_essential',
        'client_id' => 'cookies_client_id',
        'minutes' => 60 * 24 * 365,
    ],

    /*
    |--------------------------------------------------------------------------
    | Banner
    |--------------------------------------------------------------------------
    */

    'banner' => [
        'enabled' => true,
        'text' => 'We use cookies to improve your experience and measure site usage. You can accept or reject non-essential cookies.',
        'policy_url' => '/privacy-policy',
        'policy_label' => 'Cookie Policy',
        'accept_label' => 'Accept',
        'reject_label' => 'Reject',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager
    |--------------------------------------------------------------------------
    |
    | Loaded after Consent Mode defaults. Tags stay inactive until consent
    | is updated to granted.
    |
    */

    'gtm' => [
        'enabled' => (bool) env('GTM_ENABLED', false),
        'id' => env('GTM_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4
    |--------------------------------------------------------------------------
    |
    | Frontend: gtag.js when enabled (and GTM is not used, or as a fallback).
    | Backend: Measurement Protocol via Analytics::event() / Track event.
    |
    */

    'ga4' => [
        'enabled' => (bool) env('GA4_ENABLED', false),
        'measurement_id' => env('GA_MEASUREMENT_ID'),
        'api_secret' => env('GA_API_SECRET'),
        // Load gtag.js directly. Prefer GTM in production when both are set.
        'load_gtag' => (bool) env('GA4_LOAD_GTAG', true),
        // Skip Measurement Protocol when the visitor has not accepted cookies.
        'respect_consent' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Consent Mode v2 defaults (applied before GTM / gtag load)
    |--------------------------------------------------------------------------
    */

    'consent' => [
        'analytics_storage' => 'denied',
        'ad_storage' => 'denied',
        'ad_user_data' => 'denied',
        'ad_personalization' => 'denied',
        'wait_for_update' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom scripts
    |--------------------------------------------------------------------------
    |
    | position: head | body_start | body_end
    | required: true  = always injected (essential)
    |           false = deferred until Accept (Consent Mode does not cover these)
    |
    */

    'scripts' => [
        // [
        //     'position' => 'head',
        //     'required' => false,
        //     'code' => '<script>...</script>',
        // ],
    ],

];
