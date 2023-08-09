<?php

namespace App\Factory;

class UnclosedCfpConferenceFilter extends ConferenceFilter implements ConferenceApiFilter
{
    public function filter()
    {
        return $this->model->undismissed()->unclosedCfp()->approved();
    }

    public function filterApi()
    {
        return $this->model->unclosedCfp();
    }
}