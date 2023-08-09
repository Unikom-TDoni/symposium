<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Repository\SubmissionRepository;
use App\Http\Requests\SaveSubmissionReactionRequest;

class SubmissionReactionsController extends Controller
{
    public function store(
        SaveSubmissionReactionRequest $request, 
        Submission $submission,
        SubmissionRepository $submissionRepository)
    {
        $submissionRepository->storeReaction($submission, $request->validated());
        return redirect()->route('submission.edit', $submission);
    }
}