<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\Submission;
use App\Models\Talk;
use App\Models\TalkRevision;
use App\Models\User;
use Tests\IntegrationTestCase;

class SubmissionTest extends IntegrationTestCase
{
    /** @test */
    function user_can_submit_talks_via_http()
    {
        $user = User::factory()->create();
        $this->be($user);

        $conference = Conference::factory()->create();
        $talk = Talk::factory()->create(['author_id' => $user->id]);
        $revision = TalkRevision::factory()->create();
        $talk->revisions()->save($revision);

        $this->post('submissions', [
            'conferenceId' => $conference->id,
            'talkId' => $talk->id,
        ]);

        $this->assertTrue($conference->submissions->count() === 1);
    }

    /** @test */
    function user_can_unsubmit_talks_via_http()
    {
        $user = User::factory()->create();
        $this->be($user);

        $conference = Conference::factory()->create();
        $talk = Talk::factory()->create(['author_id' => $user->id]);
        $revision = TalkRevision::factory()->create();
        $talk->revisions()->save($revision);

        $submission = Submission::factory()->create([
            'talk_revision_id' => $revision->id,
            'conference_id' => $conference->id,
        ]);

        $this->delete("submissions/{$submission->id}");

        $this->assertTrue($conference->submissions->isEmpty());
    }

    /** @test */
    function user_cannot_submit_other_users_talk()
    {
        $user = User::factory()->create();
        $this->be($user);
        $otherUser = User::factory()->create([
            'email' => 'a@b.com',
        ]);

        $conference = Conference::factory()->create();
        $talk = Talk::factory()->create([
            'author_id' => $otherUser->id,
        ]);
        $revision = TalkRevision::factory()->create();
        $talk->revisions()->save($revision);

        $this->post('submissions', [
            'conferenceId' => $conference->id,
            'talkId' => $talk->id,
        ]);

        $this->assertEquals(0, $conference->submissions->count());
    }

    /** @test */
    function user_cannot_unsubmit_other_users_talk()
    {
        $user = User::factory()->create();
        $this->be($user);
        $otherUser = User::factory()->create([
            'email' => 'a@b.com',
        ]);

        $conference = Conference::factory()->create();
        $talk = Talk::factory()->create([
            'author_id' => $otherUser->id,
        ]);
        $revision = TalkRevision::factory()->create([
            'talk_id' => $talk->id,
        ]);

        $submission = Submission::factory()->create([
            'talk_revision_id' => $revision->id,
            'conference_id' => $conference->id,
        ]);

        $this->delete("submissions/{$submission->id}");

        $this->assertEquals(1, $conference->submissions->count());
        $this->assertTrue($conference->submissions->contains($submission));
    }
}