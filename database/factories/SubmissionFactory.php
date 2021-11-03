<?php

namespace Database\Factories;

use App\Models\Conference;
use App\Models\Submission;
use App\Models\TalkRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition()
    {
        return [
            'talk_revision_id' => function () {
                return TalkRevision::factory()->create()->id;
            },
            'conference_id' => function () {
                return Conference::factory()->create()->id;
            },
        ];
    }
}