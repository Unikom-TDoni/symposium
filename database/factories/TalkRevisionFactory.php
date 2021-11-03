<?php

namespace Database\Factories;

use App\Models\Talk;
use App\Models\TalkRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalkRevisionFactory extends Factory
{
    protected $model = TalkRevision::class;

    public function definition()
    {
        return [
            'title' => 'My Awesome Title',
            'type' => 'lightning',
            'length' => '9',
            'level' => 'beginner',
            'slides' => 'http://speakerdeck.com/mattstauffer/the-best-talk-ever',
            'description' => 'The best talk ever!',
            'organizer_notes' => 'No really.',
            'talk_id' => function () {
                return Talk::factory()->create()->id;
            },
        ];
    }
}