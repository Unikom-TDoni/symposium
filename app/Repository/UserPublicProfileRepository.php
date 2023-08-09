<?php

namespace App\Repository;

use App\Models\User;

class UserPublicProfileRepository extends Repository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getPublicSpeakerOrderByName($query)
    {
        return $this->model->searchPublicSpeakers($query)
            ->orderBy('name')
            ->get();
    }

    public function findPublicUserByProfileSlug($profileSlug)
    {
        return $this->model->where('profile_slug', $profileSlug)
            ->where('enable_profile', true)
            ->firstOrFail();
    }

    public function getOrderedUserPublicTalks($user)
    {
        return $this->getUserPublicTalkEloquent($user)->get()->sortByTitle();
    }

    public function findUserPublicTalk($user, $id) 
    {
        return $this->getUserPublicTalkEloquent($user)->finOrFail($id);
    }

    private function getUserPublicTalkEloquent($user)
    {
        return $user->talks()->public();
    }

    public function getUserPublicBio($user) 
    {
        return $this->getUserPublicBioEloquent($user)->get();
    }

    public function findUserPublicBio($user, $id) 
    {
        return $this->getUserPublicBioEloquent($user)->findOrFail($id);
    }

    private function getUserPublicBioEloquent($user) 
    {
        return $user->bios()->public();
    }
}