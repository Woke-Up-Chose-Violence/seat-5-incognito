@extends('web::layouts.grids.3-9')

@section('title', $region->name . ' - '. trans('woke-up-chose-violence::global.locations.browser_title'))
@section('page_header', trans('woke-up-chose-violence::global.locations.page_title'))
@section('page_description', trans('woke-up-chose-violence::global.locations.page_subtitle'))


@section('left')
@include('woke-up-chose-violence::character-map.left', ['allRegions' => $allRegions, 'region' => $region, 'characters' => $characters])
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

    var didInit = false;
    function init() {
        if (didInit) return;
        didInit = true;
        document.getElementById('legend').remove();
        document.getElementById('controls').remove();
        document.querySelectorAll("[id^=rect]").forEach(el => el.style.fill = 'white');
        document.querySelectorAll("symbol[id^=def] > a > text[id]").forEach(el => el.textContent = '');
        setTimeout(function() {
            @foreach(array_filter($characters, function ($character) { return !$character->online || !$character->online->online; }) as $character)
            try {
                if(!!document.querySelector("#rect{{ $character->location->solar_system->system_id }}")) {
                    document.querySelector("#rect{{ $character->location->solar_system->system_id }}").style.fill = '#ff000047';
                    const currentInner = document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML;
                    const newInner = '{{ $character->name }} ({{ $character->user->main_character->name }}) - Offline\n' + ((currentInner || '').replace('<title>', '').replace('</title>') || '');
                    document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML = `<title>${newInner}</title>`.replace('undefined', '');
                }
            } catch(e) {
                console.error(e);
            }        
            @endforeach
            @foreach(array_filter($characters, function ($character) { return $character->online && $character->online->online && is_null($character->location->structure) && is_null($character->location->station); }) as $character)
            try {
                if(!!document.querySelector("#rect{{ $character->location->solar_system->system_id }}")) {
                    document.querySelector("#rect{{ $character->location->solar_system->system_id }}").style.fill = '#adff2f';
                    const currentInner = document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML;
                    const newInner = '{{ $character->name }} ({{ $character->user->main_character->name }}) - In Space\n' + ((currentInner || '').replace('<title>', '').replace('</title>') || '');
                    document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML = `<title>${newInner}</title>`.replace('undefined', '');
                }
            } catch(e) {
                console.error(e);
            }        
            @endforeach
            @foreach(array_filter($characters, function ($character) { return $character->online && $character->online->online && !(is_null($character->location->structure) && is_null($character->location->station)); }) as $character)
            try {
                if(!!document.querySelector("#rect{{ $character->location->solar_system->system_id }}")) {
                    document.querySelector("#rect{{ $character->location->solar_system->system_id }}").style.fill = '#0003ff47';
                    const currentInner = document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML;
                    const newInner = '{{ $character->name }} ({{ $character->user->main_character->name }}) - Docked\n' + ((currentInner || '').replace('<title>', '').replace('</title>') || '');
                    document.querySelector("use#sys{{ $character->location->solar_system->system_id }}").innerHTML = `<title>${newInner}</title>`.replace('undefined', '');
                }
            } catch(e) {
                console.error(e);
            }        
            @endforeach
        }, 500);
    };

    var svg = document.getElementById('svgMap');
    svg.outerHTML = htmlDecode(`{{ $svg }}`);

    init();
</script>
@endpush