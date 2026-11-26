<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\Consent;

class Noscript extends Component
{
    public function render(): View
    {
        return view('cookies::noscript', [
            'gtmEnabled' => (bool) config('cookies.gtm.enabled') && config('cookies.gtm.id') && Consent::accepted(),
            'gtmId' => config('cookies.gtm.id'),
        ]);
    }
}
