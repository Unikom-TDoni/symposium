<?php

namespace App\Policies;

use App\Models\Talk;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    public function updateOrDelete(User $user, Submission $submission)
    {
        return $user->id == $submission->talkRevision->talk->author_id 
                ? Response::allow()
                : Response::denyWithStatus(401);;
    }

    public function store(User $user, Talk $talk)
    {   
        return $user->id == $talk->author_id
             ? Response::allow()
             : Response::denyWithStatus(401);
    }
}
