<?php

namespace App\Factory;

class AllConferenceFilter extends ConferenceFilter implements ConferenceApiFilter
{
    public function filter()
    {
        return $this->model->undismissed()->approved();
    }

    public function filterApi()
    {
        return $this->model;
    }
}