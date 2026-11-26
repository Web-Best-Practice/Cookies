<?php

namespace WebBestPractice\Cookies\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use WebBestPractice\Cookies\Support\Consent;

class Scripts extends Component
{
    public function __construct(public string $position = 'body_end') {}

    public function render(): View
    {
        $scripts = collect(config('cookies.scripts', []))
            ->filter(fn ($script) => ($script['position'] ?? '') === $this->position);

        return view('cookies::scripts', [
            'position' => $this->position,
            'scriptsOnLoad' => $scripts->filter(fn ($s) => ($s['required'] ?? false) || Consent::accepted())->values(),
            'scriptsDeferred' => $scripts->filter(fn ($s) => ! ($s['required'] ?? false) && ! Consent::accepted())->values(),
        ]);
    }
}
