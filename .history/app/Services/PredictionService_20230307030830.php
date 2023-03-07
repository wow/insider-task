<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Simulation;

class PredictionService
{
    /**
     * How does prediction work?
     * When entering the last 3 weeks during the group matches, we want the
     * championship rates of the teams to be estimated. You are expected to create a
     * certain championship percentage by taking into account the remaining matches of
     * the teams, either directly taking into account the points earned at that time, or by
     * adding the remaining matches of the teams along with these points to this forecasting
     * system. For example, there are 2 weeks left of the group matches and 1 team is
     * ahead by 9 points. In this case, the championship percentage of that team will be
     * 100% and the others will decrease to 0% or there is 1 week left until the end of the
     * group matches and the points of the teams in the first two rows of the group will be
     * equal and the last match will be played against each other. Here, estimates such as
     * 50%, 50% or 65%, 35% can be made based on the goals they have scored in their
     *  past matches or the teams they have beaten.
     */
    public function predict()
    {


    }


    public function getCurrentStandings()
    {
        $standings = Simulation::with('team')->orderBy('points', 'desc')->orderBy('goal_difference', 'desc')->get();

        return $standings;
    }

    public function getCurrentWeek()
    {
        $currentWeek = Simulation::max('played');

        return $currentWeek;
    }


}
