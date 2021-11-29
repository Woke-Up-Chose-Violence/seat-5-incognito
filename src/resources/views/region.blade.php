@extends('web::layouts.grids.12')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))

@section('full')

<select name="regions" id="region_list" class="form-control">
    @foreach($allRegions as $aRegion)
        <option value="{{ $aRegion->region_id }}">{{ $aRegion->name }}</option>
    @endforeach
</select>

<br />
<h3>{{ $region->name }}</h3>

<div id='svgMap'>Loading...</div>

@stop

@push('javascript')
<script>
    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }

    var svg = document.getElementById('svgMap');
    svg.outerHTML = htmlDecode(`{{ $regionSvg }}`);

    $('#region_list').on('change', (e) => {
        e.preventDefault();
        const newRegionId = e.target.value;
        let route = "{{ route('characterlocationmap.region', ['region_id' => $region->region_id]) }}".replace({{ $region->region_id }}, newRegionId);
        document.location = route;
    });

    setTimeout(function() {
        document.getElementById('legend').remove();
        document.getElementById('controls').remove();
        document.querySelectorAll("[id^=rect]").forEach(el => el.style.fill = 'white');
    });
    setTimeout(function() {
        let title;
        @foreach($characters as $character)
        try {
            document.querySelector("#rect{{ $character->location->solar_system->system_id }}").style.fill = 'gray';
            title = document.createElement('p');
            title.textContent = '{{ $character->name }} ({{ $character->user->main_character->name }})';
            document.querySelector("#def{{ $character->location->solar_system->system_id }}").appendChild(title);
        } catch(e) {
            console.error(e);
        }        
        @endforeach
    }, 500);
</script>
@endpush