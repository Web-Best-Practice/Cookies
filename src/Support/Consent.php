<?php

namespace WebBestPractice\Cookies\Support;

use Illuminate\Support\Facades\Cookie;

class Consent
{
    public static function accepted(): bool
    {
        return (bool) Cookie::get(config('cookies.cookies.all'));
    }

    public static function rejected(): bool
    {
        return (bool) Cookie::get(config('cookies.cookies.essential'));
    }

    public static function decided(): bool
    {
        return static::accepted() || static::rejected();
    }

    /**
     * Values for gtag('consent', 'default'|'update', ...).
     *
     * @return array<string, string|int>
     */
    public static function gtagState(): array
    {
        $defaults = config('cookies.consent', []);
        $granted = static::accepted();

        $state = [];

        foreach (['analytics_storage', 'ad_storage', 'ad_user_data', 'ad_personalization'] as $key) {
            $state[$key] = $granted ? 'granted' : ($defaults[$key] ?? 'denied');
        }

        if (isset($defaults['wait_for_update'])) {
            $state['wait_for_update'] = (int) $defaults['wait_for_update'];
        }

        return $state;
    }

    /**
     * @return array<string, string>
     */
    public static function grantedState(): array
    {
        return [
            'analytics_storage' => 'granted',
            'ad_storage' => 'granted',
            'ad_user_data' => 'granted',
            'ad_personalization' => 'granted',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function deniedState(): array
    {
        return [
            'analytics_storage' => 'denied',
            'ad_storage' => 'denied',
            'ad_user_data' => 'denied',
            'ad_personalization' => 'denied',
        ];
    }
}
