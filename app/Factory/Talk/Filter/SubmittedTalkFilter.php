<?php

namespace App\Factory;

class SubmittedTalkFilter extends TalkFilter
{
    public function filter()
    {
        return $this->getUserTalks()->submitted()->get();
    }
}