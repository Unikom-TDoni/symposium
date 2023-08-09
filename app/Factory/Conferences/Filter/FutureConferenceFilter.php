<?php

namespace App\Factory;

class FutureConferenceFilter extends ConferenceFilter implements ConferenceApiFilter
{
    public function filter()
    {
        return $this->model->undismissed()->future()->approved();
    }

    public function filterApi()
    {
        return $this->model->future();
    }
}