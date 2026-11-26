<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\ConfiguredScripts;
use WebBestPractice\Cookies\Support\Consent;

class Head extends Component
{
    public function render(): View
    {
        [$scriptsOnLoad, $scriptsDeferred] = ConfiguredScripts::forPosition('head');

        return view('cookies::head', [
            'consentState' => Consent::gtagState(),
            'accepted' => Consent::accepted(),
            'gtmEnabled' => (bool) config('cookies.gtm.enabled') && config('cookies.gtm.id'),
            'gtmId' => config('cookies.gtm.id'),
            'ga4Enabled' => (bool) config('cookies.ga4.enabled') && config('cookies.ga4.measurement_id'),
            'ga4LoadGtag' => (bool) config('cookies.ga4.load_gtag', true),
            'measurementId' => config('cookies.ga4.measurement_id'),
            'scriptsOnLoad' => $scriptsOnLoad,
            'scriptsDeferred' => $scriptsDeferred,
        ]);
    }
}
