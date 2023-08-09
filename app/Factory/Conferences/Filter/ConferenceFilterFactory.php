<?php

namespace App\Factory;

use App\Models\Conference;

class ConferenceFilterFactory
{
    private $model;

    public function __construct(Conference $model)
    {
        $this->model = $model;
    }

    public function getFilter($filterKey) : ConferenceFilter
    {
        return match($filterKey)
        {
            'favorites' => new FavoriteConferenceFilter($this->model),
            'dismissed' => new DismissedConferenceFilter($this->model),
            'open_cfp' => new OpenCfpConferenceFilter($this->model),
            'unclosed_cfp' => new UnclosedCfpConferenceFilter($this->model),
            'all' => new AllConferenceFilter($this->model),
            default => new FutureConferenceFilter($this->model)
        };
    }

    public function getFilterApi($filterKey) : ConferenceApiFilter
    {
        return match($filterKey)
        {
            'all' => new AllConferenceFilter($this->model),
            'future' => new FutureConferenceFilter($this->model),
            'open_cfp' => new OpenCfpConferenceFilter($this->model),
            default => new UnclosedCfpConferenceFilter($this->model)
        };
    }
}