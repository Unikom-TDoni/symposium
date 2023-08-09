<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function Contacted(User $user)
    {
        return $user->allow_profile_contact 
            ? Response::allow()
            : Response::denyWithStatus(401);;
    }
}
