<?php

namespace WebBestPractice\Cookies\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WebBestPractice\Cookies\Support\Consent;

class AnalyticsService
{
    /**
     * Send a GA4 event via Measurement Protocol.
     *
     * @param  array<string, mixed>  $params
     */
    public function event(string $name, array $params = [], bool $force = false): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        if (! $force && config('cookies.ga4.respect_consent', true) && ! Consent::accepted()) {
            return;
        }

        $payload = [
            'client_id' => $this->clientId(),
            'events' => [
                [
                    'name' => $name,
                    'params' => array_merge([
                        'engagement_time_msec' => 100,
                    ], $params),
                ],
            ],
        ];

        try {
            Http::asJson()->post($this->endpoint(), $payload);
        } catch (\Throwable $e) {
            Log::warning('Cookies analytics event failed: '.$e->getMessage(), [
                'event' => $name,
            ]);
        }
    }

    /**
     * Force-send regardless of consent (e.g. critical server-side conversions).
     *
     * @param  array<string, mixed>  $params
     */
    public function forceEvent(string $name, array $params = []): void
    {
        $this->event($name, $params, true);
    }

    public function isConfigured(): bool
    {
        return (bool) config('cookies.ga4.measurement_id')
            && (bool) config('cookies.ga4.api_secret');
    }

    protected function endpoint(): string
    {
        return 'https://www.google-analytics.com/mp/collect?measurement_id='
            .urlencode((string) config('cookies.ga4.measurement_id'))
            .'&api_secret='
            .urlencode((string) config('cookies.ga4.api_secret'));
    }

    /**
     * Prefer a stable client id from our cookie, then _ga, else generate and queue a cookie.
     */
    public function clientId(): string
    {
        $cookieName = config('cookies.cookies.client_id');

        if ($existing = Cookie::get($cookieName)) {
            return (string) $existing;
        }

        if ($fromGa = $this->clientIdFromGaCookie()) {
            $this->queueClientIdCookie($fromGa);

            return $fromGa;
        }

        $id = (string) Str::uuid();
        $this->queueClientIdCookie($id);

        return $id;
    }

    protected function clientIdFromGaCookie(): ?string
    {
        $ga = Cookie::get('_ga');

        if (! is_string($ga) || $ga === '') {
            return null;
        }

        // Format: GA1.1.XXXXXXXXXX.YYYYYYYYYY
        if (preg_match('/GA\d+\.\d+\.(.+)$/', $ga, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function queueClientIdCookie(string $id): void
    {
        $minutes = (int) config('cookies.cookies.minutes', 60 * 24 * 365);

        Cookie::queue(
            cookie(
                config('cookies.cookies.client_id'),
                $id,
                $minutes,
                '/',
                null,
                null,
                false,
                false,
                'Lax'
            )
        );
    }
}
