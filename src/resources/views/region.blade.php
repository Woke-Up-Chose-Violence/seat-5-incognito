@extends('web::layouts.grids.3-9')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))


@section('left')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Characters</h3>
    </div>
    <div class="card-body">
        <h4>Regions</h4>
        <select name="regions" id="region_list" class="form-control">
            @foreach($allRegions as $aRegion)
                <option value="{{ $aRegion->region_id }}" @if($aRegion->region_id === $region->region_id) selected @endif>{{ $aRegion->name }}</option>
            @endforeach
        </select>
        
        <h4>Characters</h4>
        <ul class="list-group list-group-unbordered mb-3">
            @foreach($characters as $character)
                <li class="list-group-item">

                    <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
                        array_merge(request()->route()->parameters, ['character' => $character])) }}">
                    {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $character->name }} ({{ $character->user->main_character->name }})
                    </a>

                    <span class="id-to-name text-muted float-right">@include('web::partials.location', ['location' => $character->location])</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@stop

@section('right')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $region->name }}</h3>
    </div>
    <div class="card-body" id='svgMap'></div>
</div>
@stop

@push('javascript')
<script>
    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }

    $('#region_list').on('change', (e) => {
        e.preventDefault();
        const newRegionId = e.target.value;
        let route = "{{ route('characterlocationmap.region', ['region_id' => $region->region_id]) }}".replace({{ $region->region_id }}, newRegionId);
        document.location = route;
    });

    var didInit = false;
    function init() {
        if (didInit) return;
        didInit = true;
        document.getElementById('legend').remove();
        document.getElementById('controls').remove();
        document.querySelectorAll("[id^=rect]").forEach(el => el.style.fill = 'white');
        setTimeout(function() {
            let title;
            @foreach($characters as $character)
            try {
                if(!!document.querySelector("#rect{{ $character->location->solar_system->system_id }}")) {
                    document.querySelector("#rect{{ $character->location->solar_system->system_id }}").style.fill = 'gray';
                    if(!!document.querySelector("#def{{ $character->location->solar_system->system_id }} title")) {
                        document.querySelector("#def{{ $character->location->solar_system->system_id }} title").remove();
                    }
                    title = document.createElement('title');
                    title.textContent = '{{ $character->name }} ({{ $character->user->main_character->name }})';
                    document.querySelector("#def{{ $character->location->solar_system->system_id }}").appendChild(title);
                }
            } catch(e) {
                console.error(e);
            }        
            @endforeach
        }, 500);
    };

    var svg = document.getElementById('svgMap');
    svg.outerHTML = htmlDecode(`{{ $regionSvg }}`);

    init();
</script>
@endpush