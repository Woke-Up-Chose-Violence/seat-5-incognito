@extends('web::layouts.grids.12')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))

@section('full')

@foreach($allRegions as $aRegion)
<a href="{{ route('characterlocationmap.region', ['region_d' => $aRegion->region_id]) }}">
    {{ $region->name }}
</a>&nbsp;-&nbsp;
@endforeach

<br />

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
</script>
@endpush