<?php

namespace App\Factory;

use Illuminate\Support\Facades\Auth;

class DismissedConferenceFilter extends ConferenceFilter
{
    public function filter()
    {
        $user = Auth::user();
        return $user->dismissedConferences()->approved();
    }
}