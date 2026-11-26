@if($show)
    <div class="wbp-cookie wbp-cookie--visible" data-cookies-banner role="dialog" aria-live="polite" aria-label="Cookie consent">
        <div class="wbp-cookie__text">
            {{ $banner['text'] }}
        </div>
        <div class="wbp-cookie__buttons">
            <button type="button" class="wbp-cookie__btn wbp-cookie__btn--accept" data-cookies-accept>
                {{ $banner['accept_label'] }}
            </button>
            <button type="button" class="wbp-cookie__btn wbp-cookie__btn--reject" data-cookies-reject>
                {{ $banner['reject_label'] }}
            </button>
            @if(!empty($banner['policy_url']))
                <a href="{{ $banner['policy_url'] }}" class="wbp-cookie__link" target="_blank" rel="noopener">
                    {{ $banner['policy_label'] ?? 'Cookie Policy' }}
                </a>
            @endif
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
