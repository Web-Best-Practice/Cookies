# Web Best Practice — Cookies

Laravel package for UK GDPR cookie consent, Google Consent Mode v2, GA4 / GTM, config-driven custom scripts, and JS + PHP event tracking.

## Install

```bash
composer require web-best-practice/cookies
php artisan vendor:publish --tag=cookies-config
```

## Blade usage

```blade
<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-cookies::head />
</head>
<body>
    <x-cookies::body-start />

    {{-- page content --}}

    <x-cookies::body-end />
</body>
</html>
```

| Tag | Includes |
|-----|----------|
| `<x-cookies::head />` | Consent Mode defaults, GTM/GA4, `head` scripts |
| `<x-cookies::body-start />` | GTM `<noscript>`, `body_start` scripts |
| `<x-cookies::body-end />` | Consent banner (+ JS API), `body_end` scripts |

## Consent Mode

GTM / gtag load after a default denied consent state:

```js
gtag('consent', 'default', {
    analytics_storage: 'denied',
    ad_storage: 'denied',
    ad_user_data: 'denied',
    ad_personalization: 'denied',
    wait_for_update: 500
});
```

- **Accept all** → `gtag('consent', 'update', { ... granted })` + run deferred custom scripts  
- **Reject all** → consent stays denied; only essential / `required` scripts run  

## Config scripts

In `config/cookies.php`:

```php
'scripts' => [
    [
        'position' => 'head', // head | body_start | body_end
        'required' => false,
        'code' => '<script>...</script>',
    ],
],
```

## JavaScript tracking

```js
WbpCookies.track('button_click', { button_id: 'checkout' });
```

## PHP tracking

```php
use WebBestPractice\Cookies\Facades\Analytics;
use WebBestPractice\Cookies\Events\Track;

// Direct
Analytics::event('purchase', [
    'transaction_id' => $order->id,
    'value' => $order->total,
    'currency' => 'GBP',
]);

// Via event / listener (package listens automatically)
event(new Track('purchase', [
    'transaction_id' => $order->id,
    'value' => $order->total,
    'currency' => 'GBP',
]));

// Ignore consent cookie (rare; e.g. trusted server-side conversion)
Analytics::forceEvent('purchase', [...]);
```

## Environment

```env
GTM_ENABLED=true
GTM_ID=GTM-XXXXXXX

GA4_ENABLED=true
GA_MEASUREMENT_ID=G-XXXXXXXX
GA_API_SECRET=xxxxxxxx
GA4_LOAD_GTAG=false
```

When GTM is enabled, prefer managing GA4 inside GTM and set `GA4_LOAD_GTAG=false`. Measurement Protocol (`GA_API_SECRET`) is still used for PHP events.
