<?php

namespace App\Factory;

abstract class TalkFilter
{
    protected function getUserTalks() 
    {
        $user = Auth::user();
        return $user->talks();
    }

    public abstract function filter();
}