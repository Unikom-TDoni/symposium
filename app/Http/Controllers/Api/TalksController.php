<?php

namespace App\Http\Controllers\Api;

use App\Repository\TalksRepository;
use App\Http\Controllers\Controller;

class TalksController extends Controller
{
    public function show(TalksRepository $talkRepository, $id)
    {
        return response()->jsonApi([
            'data' => $talkRepository->findUserTalks($id)->toArray(),
        ]);
    }
}