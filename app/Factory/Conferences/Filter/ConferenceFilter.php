<?php

namespace App\Factory;

use App\Models\Conference;

abstract class ConferenceFilter
{
    protected $model;

    public function __construct(Conference $model)
    {
        $this->model = $model;
    }

    public abstract function filter();
}