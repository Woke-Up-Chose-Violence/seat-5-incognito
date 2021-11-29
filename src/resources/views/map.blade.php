@extends('web::layouts.grids.3-9')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))

@section('left')
@include('characterlocationmap::left', ['allRegions' => $allRegions, 'region' => null, 'characters' => $characters])
@stop

@section('right')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Select A Region</h3>
    </div>
</div>
@stop