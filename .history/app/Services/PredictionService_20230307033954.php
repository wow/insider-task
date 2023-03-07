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
        $simulator = new Simulation();

        // Get current standings from the simulated data
        $simulations = $simulator->getCurrentStandings();

        // Get remaining fixtures and calculate the simulation weight score for each team
        $remainingFixtures = Fixture::where('week', '>', $simulator->getCurrentWeek())->get();


        // If there are 3 or less fixtures remaining to estimate the championship prediction
        // we will use the points of the teams to estimate the championship prediction
        // instead of using the simulation weight score
        // This is because the simulation weight score is calculated based on the points
        // of the teams and the remaining fixtures. If there are 3 or less fixtures
        // remaining, the points of the teams will be enough to estimate the championship
        // prediction.
        // check if team is already guaranteed to win the championship
        if (count($remainingFixtures) <= 3 && count($remainingFixtures) > 0) {
            $totalPoints = $simulations->sum('points');
            $simulations->each(function ($team) use ($totalPoints) {
                // check if team is already guaranteed to win the championship
                // if no other team can catch up to them anymore
                // because not enough fixtures are left to play to catch up
                if ($team->points > $totalPoints - $team->points) {
                    $team->prediction = 1;
                } else {
                    $team->prediction = $team->points / $totalPoints;
                }

                $team->prediction *= 100;

            });



            return $simulations;
        }

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
