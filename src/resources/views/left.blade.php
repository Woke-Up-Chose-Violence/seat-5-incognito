<div class="card">
    <div class="card-header">
        <h3 class="card-title">Characters</h3>
    </div>
    <div class="card-body">
        <h4>Regions</h4>
        <div class="form-group">
          <select name="regions" id="region_list" class="form-control">
              @foreach($allRegions as $aRegion)
                  <option value="{{ $aRegion->region_id }}"  @if($region && $aRegion->region_id === $region->region_id) selected @endif>{{ $aRegion->name }}</option>
              @endforeach
          </select>
        </div>
        
        <h4>In Space</h4>
        <div class="form-group">
          <ul class="list-group list-group-unbordered mb-3">
              @foreach(array_filter($characters, function ($character) { return is_null($character->structure) && is_null($character->station); }), as $character)
                  <li class="list-group-item">

                      <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(request()->route()->parameters, ['character' => $character])) }}">
                      {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                      {{ $character->name }} ({{ $character->user->main_character->name }})
                      </a>

                      <span class="id-to-name text-muted float-right">@include('web::partials.location', ['location' => $character->location])</span>
                  </li>
              @endforeach
          </ul>
        </div>

        <h4>Docked</h4>
        <div class="form-group">
          <ul class="list-group list-group-unbordered mb-3">
              @foreach(array_filter($characters, function ($character) { return !is_null($character->structure) || !is_null($character->station); }), as $character)
                  <li class="list-group-item">

                      <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(request()->route()->parameters, ['character' => $character])) }}">
                      {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                      {{ $character->name }} ({{ $character->user->main_character->name }})
                      </a>

                      <span class="id-to-name text-muted float-right">@include('web::partials.location', ['location' => $character->location])</span>
                  </li>
              @endforeach
          </ul>
        </div>
    </div>
</div>

@push('javascript')
<script>
    $('#region_list').on('change', (e) => {
        e.preventDefault();
        let route = "{{ route('characterlocationmap.region', ['region_id' => '###']) }}".replace('###', e.target.value);
        document.location = route;
    });
</script>
@endpush