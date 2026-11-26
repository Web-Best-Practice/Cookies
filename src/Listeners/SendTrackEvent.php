<?php

namespace WebBestPractice\Cookies\Listeners;

use WebBestPractice\Cookies\Events\Track;
use WebBestPractice\Cookies\Services\AnalyticsService;

class SendTrackEvent
{
    public function __construct(protected AnalyticsService $analytics) {}

    public function handle(Track $event): void
    {
        $this->analytics->event($event->name, $event->params, $event->force);
    }
}
