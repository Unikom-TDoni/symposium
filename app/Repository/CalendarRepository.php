<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\Conference;

class CalendarRepository extends Repository
{
    public function getCalendarConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasDates()->get();
    }

    public function getCalendarCfpOpeningConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasCfpStart()->get();
    }

    public function getCalendarCfpClosingConference()
    {
        return $this->getCalendarConferencesQuery()->whereHasCfpEnd()->get();
    }

    private function getCalendarConferencesQuery()
    {
        return Conference::query()
            ->approved()
            ->undismissed()
            ->whereAfter(Carbon::now()->subYear());
    }
}