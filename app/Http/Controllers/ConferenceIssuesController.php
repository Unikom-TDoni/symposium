<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Repository\ConferenceIssueRepository;
use App\Http\Requests\SaveConferenceIssueRequest;

class ConferenceIssuesController extends Controller
{
    private $conferenceIssueRepository;

    public function __construct(ConferenceIssueRepository $conferenceIssueRepository)
    {
        $this->conferenceIssueRepository = $conferenceIssueRepository;
    }

    public function create(Conference $conference)
    {
        return view('conferences.issues.create', [
            'conference' => $conference,
            'reasonOptions' => $this->conferenceIssueRepository->getReasonOptions(),
        ]);
    }

    public function store(SaveConferenceIssueRequest $request, $id)
    {
        $this->conferenceIssueRepository->store($request->validated(), $id);
        return redirect()->route('conferences.show', $id)
            ->with(['success-message' => 'Thank you for reporting this issue!']);
    }
}