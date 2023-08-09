<?php

namespace App\Repository;

use App\Models\ConferenceIssue;

class ConferenceIssueRepository extends Repository
{
    public function __construct(ConferenceIssue $model)
    {
        $this->model = $model;
    }

    public function getReasonOptions()
    {
        return ConferenceIssue::reasonOptions();
    }

    public function store($data, $id)
    {
        $data['conference_id'] = $id;
        $this->model->reportIssue($data);
    }
}