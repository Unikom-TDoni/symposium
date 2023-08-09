<?php

namespace App\Repository;

use App\Models\Talk;
use App\Models\TalkRevision;
use Illuminate\Support\Facades\Auth;
use App\Transformers\TalkForConferenceTransformer;

class TalksRepository extends Repository
{
    public function __construct(Talk $model)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($data)
    {
        $createdTalk = $this->model->create($data);
        $createdTalk->revisions()->create($data);
        return $createdTalk;
    }

    public function updateUserTalk($data, $id)
    {
        $talk = $this->findUserTalks($id);
        $talk->update($data);
        $talk->revisions()->create($data);
    }

    public function getCurrentTalkRevision($talk, $revisionId)
    {
        return $revisionId ? $talk->revisions()->findOrFail($revisionId) : $talk->current();
    }
    
    public function getFilteredSortedTalk($filterKey, $sortedKey)
    {
        return $this->sort($this->filter($filterKey), $sortedKey);
    }

    public function getSortedArchiveTalk($sortedKey)
    {
        return $this->sort($this->getUserArchivedTalk(), $sortedKey);
    }

    private function sort($talk, $sortedKey)
    {
        return match($sortedKey) 
        {
            'date' => $talk->sortByDesc('created_at'),
            default => $talk->sortBy('title')
        };
    }

    private function filter($filterKey)
    {
        $authUserTalk = $this->getUserTalks();
        return match($filterKey)
        {
            'submitted' => $authUserTalk->submitted()->get(),
            'accepted' => $authUserTalk->accepted()->get(),
            default => $authUserTalk->get()
        };
    }

    public function getTalkForConference($conference)
    {
        return $this->getSortedTalkByTitle()->map(function ($talk) use ($conference) {
            return TalkForConferenceTransformer::transform($talk, $conference);
        });
    }

    public function getSortedTalkByTitle()
    {
        $userTalk = $this->getUserTalks()->get();
        return $userTalk->sortByTitle();
    }

    public function getTalkSubmission($talk)
    {
        return $talk->flatMap(function (Talk $talk) {
            return $talk->submissions()->with('conference')->get();
        })->groupBy('conference_id');
    }

    private function getUserArchivedTalk()
    {
        return Auth::user()->archivedTalks()->get();
    }

    public function getTalkRevisionById($id)
    {
        $talk = $this->model->findOrFail($id); 
        return $talk->current();
    }

    public function restoreUserTalk($id) 
    {
        $this->findDeactiveUserTalks($id)->restore();
    }

    public function destroyUserTalk($id)
    {
        $this->findDeactiveUserTalks($id)->delete();
    }

    private function findDeactiveUserTalks($id) 
    {
        $userTalk = $this->getUserTalks();
        return $userTalk->withoutGlobalScope('active')->findOrFail($id);
    }

    public function archiveUserTalks($id)
    {
        $this->findUserTalks($id)->archive();
    }

    public function findUserTalks($id)
    {
        return $this->getUserTalks()->findOrFail($id);
    }

    private function getUserTalks() 
    {
        $user = Auth::user();
        return $user->talks();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getDefaultTalkRevision()
    {
        return new TalkRevision([
            'type' => 'seminar',
            'level' => 'beginner',
        ]);
    }
}