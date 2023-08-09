<?php

namespace App\Http\Controllers\Api;

use App\Repository\UserRepository;
use App\Http\Controllers\Controller;

class AboutMeController extends Controller
{
    public function index(UserRepository $userRepository)
    {
        return response()->jsonApi([
            'data' => $userRepository->getUserApiResource()->toArray(),
        ]);
    }
}