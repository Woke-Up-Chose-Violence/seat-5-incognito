@extends('web::layouts.grids.3-9')

@section('title', trans('woke-up-chose-violence::global.settings.browser_title'))
@section('page_header', trans('woke-up-chose-violence::global.settings.page_title'))
@section('page_description', trans('woke-up-chose-violence::global.settings.page_subtitle'))

@section('left')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Alliance Tool Settings</h3>
    </div>
    <div class="card-body">
        <form role="form" action="{{ route('woke-up-chose-violence.settings.save') }}" method="post" id="bomb-settings-form">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="skill_queue_warnings">Get notifications about skill queue issues?</label>
                <div class="radio">
                <label>
                    @if($skill_queue_warnings)
                    <input type="radio" id="skill_queue_warnings" name="skill_queue_warnings" value="0" /> No
                    @else
                    <input type="radio" id="skill_queue_warnings" name="skill_queue_warnings" value="0" checked /> No
                    @endif
                </label>
                <label>
                    @if($skill_queue_warnings)
                    <input type="radio" id="skill_queue_warnings" name="skill_queue_warnings" value="1" checked /> Yes
                    @else
                    <input type="radio" id="skill_queue_warnings" name="skill_queue_warnings" value="1" /> Yes
                    @endif
                </label>
                </div>
            </div>
        
            <div class="form-group">
                <label for="industry_warnings">Get notifications about industry jobs and pi issues?</label>
                <div class="radio">
                <label>
                    @if($industry_warnings)
                    <input type="radio" id="industry_warnings" name="industry_warnings" value="0" /> No
                    @else
                    <input type="radio" id="industry_warnings" name="industry_warnings" value="0" checked /> No
                    @endif
                </label>
                <label>
                    @if($industry_warnings)
                    <input type="radio" id="industry_warnings" name="industry_warnings" value="1" checked /> Yes
                    @else
                    <input type="radio" id="industry_warnings" name="industry_warnings" value="1" /> Yes
                    @endif
                </label>
                </div>
            </div>

            <div class="form-group">
                <label for="fc_fleet_bot">Have the Bot support your fleet MOTD with updates?</label>
                <div class="radio">
                <label>
                    @if($fc_fleet_bot)
                    <input type="radio" id="fc_fleet_bot" name="fc_fleet_bot" value="0" /> No
                    @else
                    <input type="radio" id="fc_fleet_bot" name="fc_fleet_bot" value="0" checked /> No
                    @endif
                </label>
                <label>
                    @if($fc_fleet_bot)
                    <input type="radio" id="fc_fleet_bot" name="fc_fleet_bot" value="1" checked /> Yes
                    @else
                    <input type="radio" id="fc_fleet_bot" name="fc_fleet_bot" value="1" /> Yes
                    @endif
                </label>
                </div>
            </div>
        </form>
    </div>

    <div class="card-footer clearfix">
        <button type="submit" class="btn btn-success float-right" form="bomb-settings-form">{{ trans('woke-up-chose-violence::global.save') }}</button>
    </div>
</div>
@stop

@section('right')
@stop