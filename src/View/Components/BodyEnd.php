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

        $showBanner = config('cookies.banner.enabled') && $needsConsentUi && ! Consent::decided();

        $base = dirname(__DIR__, 3).'/resources';

        return view('cookies::body-end', [
            'showBanner' => $showBanner,
            'consentGranted' => Consent::accepted(),
            'banner' => config('cookies.banner'),
            'acceptUrl' => $showBanner ? route('cookies.accept') : null,
            'rejectUrl' => $showBanner ? route('cookies.reject') : null,
            'bannerCss' => $showBanner && is_file($base.'/css/banner.css')
                ? file_get_contents($base.'/css/banner.css')
                : '',
            'bannerJs' => $showBanner && is_file($base.'/js/cookies.js')
                ? file_get_contents($base.'/js/cookies.js')
                : '',
            'scriptsOnLoad' => $scriptsOnLoad,
            'scriptsDeferred' => $scriptsDeferred,
        ]);
    }
}
