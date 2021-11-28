@extends('web::layouts.grids.12')

@section('title', trans('characterlocationmap::global.browser_title'))
@section('page_header', trans('characterlocationmap::global.page_title'))
@section('page_description', trans('characterlocationmap::global.page_subtitle'))

@section('full')

  <ul class="list-group list-group-unbordered mb-3">
    @can('global.invalid_tokens')
      @foreach($character->refresh_token->user->all_characters()->sortBy('name') as $character_info)

      <li class="list-group-item">

        @if($character_info->refresh_token)
        <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
        array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
          {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ $character_info->name }}
        </a>
        @else
        <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
        array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
          {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ $character_info->name }}
        </a>
        <button data-toggle="tooltip" title="Invalid Token" class="btn btn-sm btn-link">
            <i class="fa fa-exclamation-triangle text-danger"></i>
          </button>
        @endif

        <span class="id-to-name text-muted float-right" data-id="{{ $character_info->affiliation->corporation_id }}">{{ $character_info->affiliation->corporation->name }}</span>
      </li>

      @endforeach
    @else
      @foreach($character->refresh_token->user->characters->sortBy('name') as $character_info)

      <li class="list-group-item">

        <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(),
        array_merge(request()->route()->parameters, ['character' => $character_info])) }}">
          {!! img('characters', 'portrait', $character_info->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
          {{ $character_info->name }}
        </a>

        <span class="id-to-name text-muted float-right">@include('web::partials.location', ['location' => $character_info->location])</span>
      </li>

      @endforeach
    @endcan
  </ul>

@stop

@push('javascript')
<script>

  console.log('Include any JavaScript you may need here!');

</script>
@endpush
