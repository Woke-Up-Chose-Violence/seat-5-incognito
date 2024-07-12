@extends('web::layouts.grids.3-9')

@section('title', $system->name . ' - '. trans('woke-up-chose-violence::global.browser_title'))
@section('page_header', trans('woke-up-chose-violence::global.page_title'))
@section('page_description', trans('woke-up-chose-violence::global.page_subtitle'))


@section('left')
@include('woke-up-chose-violence::character-map.left', ['allRegions' => $allRegions, 'region' => $region, 'system' => $system, 'characters' => $characters])
@stop

@section('right')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $system->name }} in {{ $region->name }}</h3>
    </div>
    <div class="card-body">
        <p>I have absolutely no idea what to put here. Enjoy your list, I guess.</p>
    </div>
</div>
@stop