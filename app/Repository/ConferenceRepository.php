<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\Conference;
use Illuminate\Support\Facades\Auth;

class ConferenceRepository extends Repository
{
    public function __construct(Conference $model) 
    {
        $this->model = $model;
    }

    public function update($id, $data)
    {
        $this->getById($id)->update($data);
    }

    public function getById($id) 
    {
        return $this->model->findOrFail($id);
    }

    public function deleteUserConference($id)
    {
        $this->findUserConference($id)->delete();
    }

    private function findUserConference($id)
    {
        return $this->getUserConferences()->findOrFail($id);
    }

    private function getUserConferences() 
    {
        $user = Auth::user();
        return $user->conferences();
    }

    public function getModel() 
    {
        return $this->model;
    }

    public function create($data) 
    {
        return $this->model->create($data);
    }

    public function findByAuthUserRole($id) 
    {
        if(Auth::guest()) return $this->model->approved()->findOrFail($id);
        return Auth::user()->isAdmin() ? $this->model->withoutGlobalScope('notRejected')->findOrFail($id)
            : $this->getById($id);
    }

    public function sort($conference, $sortedKey) 
    {
        return match($sortedKey)
        {
            'alpha' => $conference->orderBy('title'),
            'date' => $conference->orderBy('starts_at'),
            'opening_next' => $conference->orderByRaw('cfp_ends_at IS NULL, cfp_ends_at ASC'),
            default => $conference->orderByRaw('cfp_ends_at IS NULL, cfp_ends_at ASC')
        };
    }

    public function isSelectedConferencesFavorited($id)
    {
        return $this->getById($id)->isFavorited();
    }

    public function isSelectedConferencesDissmised($id)
    {
        return $this->getById($id)->isDismissed();
    }

    public function attachUserDismissedConference($id)
    {
        $this->getUserDismissedConference()->attach($id);
    }

    public function detachUserDismissedConference($id)
    {
        $this->getUserDismissedConference()->detach($id);
    }

    private function getUserDismissedConference()
    {
        $user = Auth::user();
        return $user->dismissedConferences();
    }
    
    public function attachUserFavoriteConference($id)
    {
        $this->getUserFavoritedConferences()->attach($id);
    }

    public function detachUserFavoriteConference($id)
    {
        $this->getUserFavoritedConferences()->detach($id);
    }

    private function getUserFavoritedConferences()
    {
        $user = Auth::user();
        return $user->favoritedConferences();
    }

    public function getCalendarConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasDates()->get();
    }

    public function getCalendarCfpOpeningConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasCfpStart()->get();
    }

    public function getCalendarCfpClosingConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasCfpEnd()->get();
    }

    private function getCalendarConferencesQuery()
    {
        return $this->model->query()
            ->approved()
            ->undismissed()
            ->whereAfter(Carbon::now()->subYear());
    }

    public function getFeaturedConferences($limit)
    {
        $featuredConferece = $this->model->whereFeatured();
        return $featuredConferece->limit($limit)->get();
    }

    public function reverseSort($conference, $sortedDirectionKey)
    {
        if($sortedDirectionKey == 'desc')
            $conference->reverse();
        return $conference;
    }
}