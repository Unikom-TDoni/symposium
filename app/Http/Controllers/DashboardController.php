<?php

namespace App\Http\Controllers;

use App\Repository\BiosRepository;
use App\Repository\TalksRepository;

class DashboardController extends Controller
{
    public function index(BiosRepository $biosRepository, TalksRepository $talksRepository)
    {
        $talks = $talksRepository->getSortedTalkByTitle();
        return view('dashboard', [
            'bios' => $biosRepository->getUserBio(),
            'submissionsByConference' => $talksRepository->getTalkSubmission($talks),
            'talks' => $talks,
        ]);
    }
}