<?php

namespace App\Factory;

class OpenCfpConferenceFilter extends ConferenceFilter implements ConferenceApiFilter
{
    public function filter()
    {
        return $this->model->undismissed()->openCfp()->approved();
    }

    public function filterApi()
    {
        return $this->model->openCfp();
    }
}