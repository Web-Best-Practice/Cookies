<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\ConfiguredScripts;
use WebBestPractice\Cookies\Support\Consent;

class BodyStart extends Component
{
    public function render(): View
    {
        [$scriptsOnLoad, $scriptsDeferred] = ConfiguredScripts::forPosition('body_start');

        return view('cookies::body-start', [
            'gtmEnabled' => (bool) config('cookies.gtm.enabled') && config('cookies.gtm.id') && Consent::accepted(),
            'gtmId' => config('cookies.gtm.id'),
            'scriptsOnLoad' => $scriptsOnLoad,
            'scriptsDeferred' => $scriptsDeferred,
        ]);
    }
}
