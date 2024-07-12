@extends('web::layouts.grids.3-9')

@section('title', trans('woke-up-chose-violence::global.browser_title'))
@section('page_header', trans('woke-up-chose-violence::global.page_title'))
@section('page_description', trans('woke-up-chose-violence::global.page_subtitle'))

@section('left')
@include('woke-up-chose-violence::character-map.left', ['allRegions' => $allRegions, 'region' => null, 'characters' => $characters])
@stop

@section('right')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Select A Region</h3>
    </div>
    <div class="card-body">
        <p>Please choose a Region from the dropdown on the left.</p>
    </div>
</div>
@stop