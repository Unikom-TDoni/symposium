<?php

namespace App\Repository;

use App\Models\Submission;

class SubmissionRepository extends Repository
{
    public function __construct(Submission $model)
    {
        $this->model = $model;
    }

    public function store($conference, $talkRevision)
    {
        return $conference->submissions()->create(['talk_revision_id' => $talkRevision->id]);
    }

    public function storeReaction(Submission $submission, $reaction)
    {
        $submission->addReaction($reaction);
    }

    public function getSubmissionTalk($submission)
    {
        return $submission->talkRevision->talk;
    }

    public function update($submission, $data) 
    {
        $response = $submission->firstOrCreateResponse($data['response']);
        $response->reason = $data['reason'];
        $response->save();
    }

    public function delete($submission)
    {
        $submission->delete();
    }

    public function getSubmissionByTalkRevisionId($talkRevision)
    {
        return $this->model
            ->where('talk_revision_id', $talkRevision->id)
            ->with(['conference', 'acceptance', 'rejection'])
            ->get();
    }

    public function loadRelation($submission)
    {
        $submission->load([
            'acceptance',
            'rejection',
            'reactions',
        ]);
    }
}