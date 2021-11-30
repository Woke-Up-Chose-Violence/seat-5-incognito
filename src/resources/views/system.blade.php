@extends('web::layouts.grids.3-9')

@section('title', $system->name . ' - '. trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))


@section('left')
@include('characterlocationmap::left', ['allRegions' => $allRegions, 'region' => $region, 'system' => $system, 'characters' => $characters])
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