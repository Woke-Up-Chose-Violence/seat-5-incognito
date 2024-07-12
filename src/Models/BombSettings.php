<?php

namespace WokeUpChoseViolence\Seat5Incognito\Models;

use Illuminate\Database\Eloquent\Model;

class BombSettings extends Model
{
    protected $table = 'bomb_settings';

    protected $casts = [
        'skill_queue_warnings' => 'boolean',
        'industry_warnings' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'skill_queue_warnings',
        'industry_warnings',
    ];

    protected $attributes = [
        'skill_queue_warnings' => false,
        'industry_warnings' => false,
    ];
}