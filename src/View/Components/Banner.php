<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\Consent;

class Banner extends Component
{
    public function render(): View
    {
        $optionalScripts = collect(config('cookies.scripts', []))
            ->contains(fn ($script) => ! ($script['required'] ?? false));

        $needsConsentUi = $optionalScripts
            || config('cookies.gtm.enabled')
            || config('cookies.ga4.enabled');

        $base = dirname(__DIR__, 3).'/resources';

        return view('cookies::banner', [
            'show' => config('cookies.banner.enabled') && $needsConsentUi && ! Consent::decided(),
            'banner' => config('cookies.banner'),
            'acceptUrl' => route('cookies.accept'),
            'rejectUrl' => route('cookies.reject'),
            'bannerCss' => is_file($base.'/css/banner.css') ? file_get_contents($base.'/css/banner.css') : '',
            'bannerJs' => is_file($base.'/js/cookies.js') ? file_get_contents($base.'/js/cookies.js') : '',
        ]);
    }
}
