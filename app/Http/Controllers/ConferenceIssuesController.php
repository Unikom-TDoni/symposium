<?php

namespace App\Http\Controllers;

use App\Models\Conference;

class ConferenceIssuesController extends Controller
{
    public function create(Conference $conference)
    {
        return view('conferences.issues.create', [
            'conference' => $conference,
        ]);
    }

    public function store(Conference $conference)
    {
        $conference->reportIssue(
            request('reason'),
            request('note'),
        );

        return redirect()
            ->route('conferences.issues.create', $conference)
            ->with(['success-message' => 'Thank you for reporting this issue!']);
    }
}
