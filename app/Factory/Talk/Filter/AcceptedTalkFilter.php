<?php

namespace App\Factory;

class AcceptedTalkFilter extends TalkFilter
{
    public function filter()
    {
        return $this->getUserTalks()->accepted()->get();
    }
}