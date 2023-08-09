<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Repository\TalksRepository;
use App\Repository\ConferenceRepository;
use App\Repository\SubmissionRepository;
use App\Http\Requests\UpdateSubmissionRequest;

class SubmissionsController extends Controller
{
    private $submissionRepository;
    
    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function store(Request $request, 
        TalksRepository $talkRepository, 
        ConferenceRepository $conferenceRepository)
    {
        $this->authorize('store', [Submission::class, $talkRepository->getById($request->input('talkId'))]);

        $submission = $this->submissionRepository->store(
            $conferenceRepository->getById($request->input('conferenceId')), 
            $talkRepository->getTalkRevisionById($request->input('talkId'))
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Talk Submitted',
            'submissionId' => $submission->id,
        ]);
    }

    public function edit(Submission $submission)
    {
        $this->submissionRepository->loadRelation($submission);
        return view('submissions.edit', [
            'submission' => $submission,
            'conference' => $submission->conference,
        ]);
    }

    public function update(Submission $submission, UpdateSubmissionRequest $request)
    {
        $this->submissionRepository->update($submission, $request->validated());
        return redirect()->route('talks.show', $this->submissionRepository->getSubmissionTalk($submission))
            ->with('success-message', 'Successfully updated submission.');
    }

    public function destroy(Submission $submission)
    {
        $this->submissionRepository->delete($submission);
        return response()->json(['status' => 'success', 'message' => 'Talk Un-Submitted']);
    }
}