<?php

namespace App\Factory;

use Illuminate\Support\Facades\Auth;

class FavoriteConferenceFilter extends ConferenceFilter
{
    public function filter()
    {
        $user = Auth::user();
        return $user->favoritedConferences()->approved();
    }
}