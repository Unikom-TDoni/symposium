<?php

namespace App\Factory;

class TalkFilterFactory
{
    public function getFilter($filterKey) : TalkFilter
    {
        return match($filterKey)
        {
            'submitted' => new SubmittedTalkFilter(),
            'accepted' =>  new AcceptedTalkFilter(),
            default => new UserTalkFilter()
        };
    }
}