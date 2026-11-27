# Web Best Practice — Cookies

Laravel package for UK GDPR cookie consent, Google Consent Mode v2, GA4 / GTM, config-driven custom scripts, and JS + PHP event tracking.

## Install

This package is **not** on Packagist. Add it as a custom VCS repository in your project's `composer.json`:

```json
{
    "require": {
        "web-best-practice/cookies": "^1.1"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Web-Best-Practice/Cookies"
        }
    ]
}
```

Then install and publish the config:

```bash
composer update web-best-practice/cookies
php artisan vendor:publish --tag=cookies-config
```

Or in one step after the `repositories` entry is present:

```bash
composer require web-best-practice/cookies
php artisan vendor:publish --tag=cookies-config
```

### GitHub authentication

Composer may call the GitHub API when resolving VCS packages. On servers (e.g. Cloudways), an expired or invalid token in `auth.json` causes `Bad credentials`.

- For this **public** repo, remove a bad token if present:
  ```bash
  composer config -g --unset github-oauth.github.com
  composer clear-cache
  composer install
  ```
- Or set a valid token (needed for private repos, or if GitHub rate-limits unauthenticated API use):
  ```bash
  composer config -g github-oauth.github.com YOUR_GITHUB_TOKEN
  ```

See [Composer: Authentication for private packages](https://getcomposer.org/doc/articles/authentication-for-private-packages.md#github-oauth).

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
