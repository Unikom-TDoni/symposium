<?php

namespace App\Policies;

use App\Models\Conference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConferencePolicy
{
    use HandlesAuthorization;

    public function edita(Conference $conference)
    {
        return $conference->author_id !== Auth::id() && !Auth::user()->isAdmin() 
            ? Response::deny('User ' . Auth::id() . " tried to edit a conference they don't own.")
            : Response::allow();
    }
}