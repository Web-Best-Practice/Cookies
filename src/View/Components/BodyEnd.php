<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\ConfiguredScripts;
use WebBestPractice\Cookies\Support\Consent;

class BodyEnd extends Component
{
    public function render(): View
    {
        [$scriptsOnLoad, $scriptsDeferred] = ConfiguredScripts::forPosition('body_end');

        $needsConsentUi = ConfiguredScripts::hasOptional()
            || config('cookies.gtm.enabled')
            || config('cookies.ga4.enabled');

        $base = dirname(__DIR__, 3).'/resources';

        return view('cookies::body-end', [
            'showBanner' => config('cookies.banner.enabled') && $needsConsentUi && ! Consent::decided(),
            'banner' => config('cookies.banner'),
            'acceptUrl' => route('cookies.accept'),
            'rejectUrl' => route('cookies.reject'),
            'bannerCss' => is_file($base.'/css/banner.css') ? file_get_contents($base.'/css/banner.css') : '',
            'bannerJs' => is_file($base.'/js/cookies.js') ? file_get_contents($base.'/js/cookies.js') : '',
            'scriptsOnLoad' => $scriptsOnLoad,
            'scriptsDeferred' => $scriptsDeferred,
        ]);
    }
}
