@extends('web::layouts.grids.12')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))

@section('full')

<h3>{{ $region->name }}</h3>

<div id='svgMap'>Loading...</div>

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

@stop

@push('javascript')
<script>
    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }

    var svg = document.getElementById('svgMap');
    svg.outerHTML = htmlDecode(`{{ $regionSvg }}`);

    setTimeout(function() {
        document.getElementById('legend').remove();
        document.getElementById('controls').remove();
        document.querySelectorAll("[id^=rect]").forEach(el => el.style.fill = 'white');
    });
</script>

@foreach($characters as $character)
<script>
    setTimeout(function() {
        document.querySelector("#sys{{ $character->location->solar_system->name }} > a")
        document.querySelector("#rect{{ $character->location->solar_system->solar_system_id }}").forEach(el => el.style.fill = 'gray');
    }, 500);
</script>
@endforeach
@endpush