<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factory\ConferenceFilterFactory;
use App\Repository\ConferenceRepository;

class ConferencesController extends Controller
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    public function index(Request $request, ConferenceFilterFactory $conferenceFilterFactory)
    {
        $conferenceFilterApi = $conferenceFilterFactory->getFilterApi($request->input('filter'));
        $parsedSortInput = $this->getParseIndexApiInput($request->input('sort'));
        $sortedConference = $this->conferenceRepository->sort(
            $conferenceFilterApi->filterApi(), $parsedSortInput['sort']);
        return response()->jsonApi([
            'data' => $this->conferenceRepository->reverseSort(
                $sortedConference->get(), 'asc')->toArray(),
        ]);
    }

    private function getParseIndexApiInput($sort)
    {
        $sortDirection = 'asc';
        if ($sort && substr($sort, 0, 1) == '-') 
        {
            $sort = substr($sort, 1);
            $sortDirection = 'desc';
        } else $sort = 'closing_next';
        return [
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ];
    }

    public function show($id)
    {
        return response()->jsonApi([
            'data' => $this->conferenceRepository->getById($id)->toArray(),
        ]);
    }
}
