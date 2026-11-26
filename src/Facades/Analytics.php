<?php

namespace WebBestPractice\Cookies\Facades;

use Illuminate\Support\Facades\Facade;
use WebBestPractice\Cookies\Services\AnalyticsService;

/**
 * @method static void event(string $name, array $params = [], bool $force = false)
 * @method static void forceEvent(string $name, array $params = [])
 * @method static bool isConfigured()
 * @method static string clientId()
 *
 * @see AnalyticsService
 */
class Analytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AnalyticsService::class;
    }
}
