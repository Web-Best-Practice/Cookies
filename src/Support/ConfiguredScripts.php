<?php

namespace WebBestPractice\Cookies\Support;

use Illuminate\Support\Collection;

class ConfiguredScripts
{
    /**
     * @return array{0: Collection, 1: Collection}
     */
    public static function forPosition(string $position): array
    {
        $scripts = collect(config('cookies.scripts', []))
            ->filter(fn ($script) => ($script['position'] ?? '') === $position);

        $onLoad = $scripts
            ->filter(fn ($s) => ($s['required'] ?? false) || Consent::accepted())
            ->values();

        $deferred = $scripts
            ->filter(fn ($s) => ! ($s['required'] ?? false) && ! Consent::accepted())
            ->values();

        return [$onLoad, $deferred];
    }

    public static function hasOptional(): bool
    {
        return collect(config('cookies.scripts', []))
            ->contains(fn ($script) => ! ($script['required'] ?? false));
    }
}
