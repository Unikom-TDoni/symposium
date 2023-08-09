<?php

namespace App\Http\Controllers;

use App\Repository\ConferenceRepository;
use App\Http\Resources\CalendarEventCollection;

class CalendarController extends Controller
{
    public function index(ConferenceRepository $conferenceRepository)
    {
        return view('calendar', [
            'events' => CalendarEventCollection::make([])
                ->addConferences($conferenceRepository->getCalendarConference())
                ->addCfpOpenings($conferenceRepository->getCalendarCfpOpeningConference())
                ->addCfpClosings($conferenceRepository->getCalendarCfpClosingConference())
                ->toJson(),
        ]);
    }
}