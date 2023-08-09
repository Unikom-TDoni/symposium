<?php

namespace App\Http\Controllers;

use App\Services\Currency;
use Illuminate\Http\Request;
use App\Repository\TalksRepository;
use Illuminate\Support\Facades\Event;
use App\Repository\ConferenceRepository;
use App\Factory\ConferenceFilterFactory;
use App\Http\Requests\SaveConferenceRequest;

class ConferencesController extends Controller
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    public function index(Request $request, ConferenceFilterFactory $conferenceFilterFactory)
    {
        $conferenceFilter = $conferenceFilterFactory->getFilter($request->input('filter'));
        $sortedConference = $this->conferenceRepository->sort($conferenceFilter->filter(), $request->input('sort'));
        return view('conferences.index', [
            'conferences' => $sortedConference->paginate(10)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('conferences.create', [
            'conference' => $this->conferenceRepository->getModel(),
            'currencies' => Currency::all(),
        ]);
    }

    public function store(SaveConferenceRequest $request)
    {
        $createdConference = $this->conferenceRepository->create($request->validated());
        Event::dispatch('new-conference', [$createdConference]);
        return redirect()->route('conferences.show', $createdConference->id)
            ->with('success-message', 'Successfully created new conference.');
    }

    public function show($id, TalksRepository $talksRepository)
    {
        $conference = $this->conferenceRepository->findByAuthUserRole($id);
        if (auth()->guest()) return $this->showPublic($conference);
        return view('conferences.show', [
            'conference' => $conference,
            'talks' => $talksRepository->getTalkForConference($conference),
        ]);
    }

    private function showPublic($conference)
    {
        return view('conferences.showPublic', [
            'conference' => $conference,
        ]);
    }

    public function edit($id)
    {
        $conference = $this->conferenceRepository->getById($id);
        $this->authorize('edit', $conference);
        return view('conferences.edit', [
            'conference' => $conference,
            'currencies' => Currency::all(),
        ]);
    }

    public function update(SaveConferenceRequest $request, $id)
    {
        $this->conferenceRepository->update($id, $request->validated());
        return redirect()->route('conferences.show', $id)
            ->with('success-message', 'Successfully edited conference.');
    }

    public function destroy($id)
    {
        $this->conferenceRepository->deleteUserConference($id);
        return redirect()->route('conferences.index')
            ->with('success-message', 'Conference successfully deleted.');
    }

    public function dismiss($id)
    {
        if ($this->conferenceRepository->isSelectedConferencesFavorited($id))
            return redirect()->back();

        $this->conferenceRepository->attachUserDismissedConference($id);

        return redirect()->back();
    }

    public function undismiss($id)
    {
        $this->conferenceRepository->detachUserDismissedConference($id);
        return redirect()->back();
    }

    public function favorite($id)
    {
        if ($this->conferenceRepository->isSelectedConferencesDissmised($id))
            return redirect()->back();

        $this->conferenceRepository->attachUserFavoriteConference($id);
        return redirect()->back();
    }

    public function unfavorite($id)
    {
        $this->conferenceRepository->detachUserFavoriteConference($id);
        return redirect()->back();
    }
}