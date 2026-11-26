@if($showBanner)
    <div class="wbp-cookie wbp-cookie--visible" data-cookies-banner role="dialog" aria-live="polite" aria-label="Cookie consent">
        <div class="wbp-cookie__icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="52" height="52" fill="none">
                <circle cx="32" cy="32" r="28" fill="#E8B86D"/>
                <circle cx="32" cy="32" r="28" fill="url(#wbpCookieShade)" fill-opacity="0.25"/>
                <circle cx="20" cy="24" r="3.2" fill="#6B4F2A"/>
                <circle cx="34" cy="18" r="2.4" fill="#6B4F2A"/>
                <circle cx="44" cy="28" r="2.8" fill="#6B4F2A"/>
                <circle cx="24" cy="38" r="2.6" fill="#6B4F2A"/>
                <circle cx="38" cy="40" r="3.4" fill="#6B4F2A"/>
                <circle cx="46" cy="42" r="2.2" fill="#6B4F2A"/>
                <defs>
                    <radialGradient id="wbpCookieShade" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(22 20) rotate(45) scale(48)">
                        <stop stop-color="#fff"/>
                        <stop offset="1" stop-color="#C48A3A"/>
                    </radialGradient>
                </defs>
            </svg>
        </div>

        <div class="wbp-cookie__body">
            @if(!empty($banner['title']))
                <div class="wbp-cookie__title">{{ $banner['title'] }}</div>
            @endif
            <div class="wbp-cookie__text">
                {{ $banner['text'] }}
                @if(!empty($banner['policy_url']))
                    <a href="{{ $banner['policy_url'] }}" class="wbp-cookie__link" target="_blank" rel="noopener">
                        {{ $banner['policy_label'] ?? 'Cookie Policy' }}
                    </a>
                @endif
            </div>
        </div>

        <div class="wbp-cookie__buttons">
            <button type="button" class="wbp-cookie__btn wbp-cookie__btn--accept" data-cookies-accept>
                {{ $banner['accept_label'] }}
            </button>
            <button type="button" class="wbp-cookie__btn wbp-cookie__btn--reject" data-cookies-reject>
                {{ $banner['reject_label'] }}
            </button>
        </div>
    </div>
@endif

@once
    <style>{!! $bannerCss !!}</style>
    <script>
        window.WbpCookies = window.WbpCookies || {};
        window.WbpCookies.routes = {
            accept: @json($acceptUrl),
            reject: @json($rejectUrl),
        };
    </script>
    <script>{!! $bannerJs !!}</script>
@endonce

@include('cookies::partials.script-blocks', [
    'position' => 'body_end',
    'scriptsOnLoad' => $scriptsOnLoad,
    'scriptsDeferred' => $scriptsDeferred,
])
