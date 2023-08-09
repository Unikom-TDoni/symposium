<?php

namespace App\Repository;

use App\Models\User;
use App\Models\Conference;

class HomeRepository extends Repository
{
    public function getLimitPublicSpeaker()
    {
        return User::whereFeatured()->limit(6)->get();
    }

    public function getLimitConference()
    {
        return Conference::whereFeatured()->limit(3)->get();
    }
}