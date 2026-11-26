@foreach($scriptsOnLoad as $script)
    {!! $script['code'] !!}
@endforeach

@if($scriptsDeferred->isNotEmpty())
    <template data-cookies-scripts="{{ $position }}">
        @foreach($scriptsDeferred as $script)
            {!! $script['code'] !!}
        @endforeach
    </template>
@endif
