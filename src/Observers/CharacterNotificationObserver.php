<?php

namespace WokeUpChoseViolence\Seat5Incognito\Observers;

use Seat\Eveapi\Models\Character\CharacterNotification;

class CharacterNotificationObserver
{
    public function created(CharacterNotification $notification)
    {
        if (CharacterNotification::where('notification_id', '=', $notification->notification_id)->get()->count() === 1)
        {

        }
    }
}
