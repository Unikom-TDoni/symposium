<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\TalksRepository;
use App\Http\Requests\SaveTalkRequest;
use App\Repository\SubmissionRepository;

class TalksController extends Controller
{
    private $talkRepository;

    public function __construct(TalksRepository $talkRepository) 
    {
        $this->talkRepository = $talkRepository;
    }

    public function index(Request $request)
    {
        return view('talks.index', [
            'talks' => $this->talkRepository->getFilteredSortedTalk(
                $request->input('filter'), $request->input('sort'))
        ]);
    }

    public function create()
    {
        return view('talks.create', [
            'current' => $this->talkRepository->getDefaultTalkRevision(), 
            'talk' => $this->talkRepository->getModel()]
        );
    }

    public function store(SaveTalkRequest $request)
    {
        $createdTalk = $this->talkRepository->create($request->validated());
        return redirect()->route('talks.index', $createdTalk->id)
            ->with('success-message', 'Successfully created new talk.');
    }

    public function edit($id)
    {
        $talk = $this->talkRepository->findUserTalks($id);
        return view('talks.edit', [
            'talk' => $talk,
            'current' => $talk->current(),
        ]);
    }

    public function update(SaveTalkRequest $request, $id)
    {
        $this->talkRepository->updateUserTalk($request->validated(), $id);
        return redirect()->route('talks.show', $id)
            ->with('success-message', 'Successfully edited talk.');
    }

    public function show(Request $request, $id, SubmissionRepository $submissionRepository)
    {
        $talk = $this->talkRepository->findUserTalks($id);
        $current = $this->talkRepository->getCurrentTalkRevision($talk, $request->input('revision'));
        return view('talks.show', [
            'talk' => $talk,
            'showingRevision' => $request->filled('revision'),
            'current' => $current,
            'submissions' => $submissionRepository->getSubmissionByTalkRevisionId($current),
        ]);
    }

    public function destroy($id)
    {
        $this->talkRepository->destroyUserTalk($id);
        return redirect()->route('talks.index')
            ->with('success-message', 'Successfully deleted talk.');
    }

    public function archiveIndex(Request $request)
    {
        return view('talks.archive', [
            'talks' => $this->talkRepository
                ->getSortedArchiveTalk($request->input('sort')),
        ]);
    }

    public function archive($id)
    {
        $this->talkRepository->archiveUserTalks($id);
        return redirect('talks')
            ->with('success-message', 'Successfully archived talk.');
    }

    public function restore($id)
    {
        $this->talkRepository->restoreUserTalk($id);
        return redirect('archive')
            ->with('success-message', 'Successfully restored talk.');
    }
}