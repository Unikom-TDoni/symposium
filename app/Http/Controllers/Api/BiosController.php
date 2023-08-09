<?php

namespace App\Http\Controllers\Api;

use App\Repository\BiosRepository;
use App\Http\Controllers\Controller;

class BiosController extends Controller
{
    public function show(BiosRepository $biosRepository, $id)
    {
        return response()->jsonApi([
            'data' => $biosRepository->findUserBio($id)->toArray(),
        ]);
    }
}