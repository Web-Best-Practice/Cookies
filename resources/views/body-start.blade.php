@if($gtmEnabled)
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

@include('cookies::partials.script-blocks', [
    'position' => 'body_start',
    'scriptsOnLoad' => $scriptsOnLoad,
    'scriptsDeferred' => $scriptsDeferred,
])
