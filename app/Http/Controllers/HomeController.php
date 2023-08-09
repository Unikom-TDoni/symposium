<?php

namespace App\Http\Controllers;

use App\Repository\UserRepository;
use App\Repository\ConferenceRepository;

class HomeController extends Controller
{
    public function index(UserRepository $userRepository, ConferenceRepository $conferenceRepository)
    {
        return view('home', [
            'speakers' => $userRepository->getFeaturedUser(6),
            'conferences' => $conferenceRepository->getFeaturedConferences(3),
        ]);
    }
}