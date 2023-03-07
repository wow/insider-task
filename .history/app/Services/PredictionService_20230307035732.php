<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Simulation;
use App\Models\Team;

class PredictionService
{

    public function championshipPrediction($remainingWeeks)
    {
        $teams = Team::all();

        // Calculate current points for each team
        foreach ($teams as $team) {
            $team->points = $team->currentPoints();
        }

        // Create a new instance of the Simulation class
        $simulation = new Simulation($teams, $remainingWeeks);

        // Calculate simulation weight score for each team
        foreach ($teams as $team) {
            $team->simulationWeightScore = $simulation->calculateSimulationWeightScore($team);
        }

        // Sort teams by current points and simulation weight score
        $teams = $teams->sortByDesc('points')->sortByDesc('simulationWeightScore');

        $teamCount = $teams->count();

        // Calculate championship prediction for each team
        foreach ($teams as $key => $team) {
            // If there is only one team or the current team is already ahead by a significant margin, set championship rate to 100%
            if ($teamCount === 1 || ($key === 0 && ($team->points - $teams[$key+1]->points) >= $remainingWeeks)) {
                $team->championshipRate = 100;
            } else {
                // Otherwise, calculate the championship rate based on remaining matches and simulation weight score
                $remainingMatches = $teamCount - $key - 1;
                $championshipRate = ($team->simulationWeightScore / $remainingMatches) * 100;

                $team->championshipRate = $championshipRate;
            }
        }

        return $teams;
    }

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
    public function predict($week = 2)
    {
        // Get all teams
        $teams = Team::all();

        // Calculate points and goal differences for each team
        foreach ($teams as $team) {
            $team->points = 0;
            $team->goal_difference = 0;
            $team->goals_for = 0;
            $team->goals_against = 0;

            // Calculate points and goal differences from previous games
            $teamStats = Simulation::where('team_id', $team->id)->first();
            if ($teamStats) {
                $team->points = $teamStats->points;
                $team->goal_difference = $teamStats->goal_difference;
                $team->goals_for = $teamStats->goals_for;
                $team->goals_against = $teamStats->goals_against;
            }

            // Calculate points and goal differences from future games
            $fixtures = Fixture::with('games')->where('week', '>', $week)->get();
            foreach ($fixtures as $fixture) {
                $homeTeam = $fixture->homeTeam()->first();
                $awayTeam = $fixture->awayTeam()->first();

                $homeGoals = $fixture->games->first()->home_goals;
                $awayGoals = $fixture->games->first()->away_goals;

                if ($homeTeam->id === $team->id) {
                    $team->goals_for += $homeGoals;
                    $team->goals_against += $awayGoals;
                    if ($homeGoals > $awayGoals) {
                        $team->points += 3;
                    } elseif ($homeGoals === $awayGoals) {
                        $team->points += 1;
                    }
                } elseif ($awayTeam->id === $team->id) {
                    $team->goals_for += $awayGoals;
                    $team->goals_against += $homeGoals;
                    if ($awayGoals > $homeGoals) {
                        $team->points += 3;
                    } elseif ($awayGoals === $homeGoals) {
                        $team->points += 1;
                    }
                }
            }
        }

        // Calculate championship percentage for each team
        $maxPoints = $teams->max('points');
        $teamsWithMaxPoints = $teams->where('points', $maxPoints);
        if ($teamsWithMaxPoints->count() === 1) {
            $teamsWithMaxPoints->first()->championship_percentage = 100;
        } else {
            $totalWeightScore = 0;
            foreach ($teamsWithMaxPoints as $team) {
                $totalWeightScore += $team->simulation()->calculateSimulationWeightScore();
            }

            foreach ($teamsWithMaxPoints as $team) {
                $teamWeightScore = $team->simulation()->calculateSimulationWeightScore();
                $team->championship_percentage = round($teamWeightScore / $totalWeightScore * 100, 2);
            }
        }

        // Sort teams by points and goal difference
        $teams = $teams->sortByDesc('points')->sortByDesc('goal_difference');

        return $teams;
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