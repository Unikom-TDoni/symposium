<?php

namespace App\Factory;

class UserTalkFilter extends TalkFilter
{
    public function filter()
    {
        return $this->getUserTalks()->get();
    }
}