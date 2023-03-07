<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Simulation;
use App\Models\Team;

class PredictionService
{
    public function predictChampionshipRates($lastWeeks = 3)
    {
        // Get all teams and their simulation scores from the simulation table
        $teams = Team::with('simulations')->get();

        // Calculate the maximum points each team can earn in the remaining weeks
        $maxPoints = $lastWeeks * 3;

        // Calculate the simulation weight score for each team
        foreach ($teams as $team) {
            $team->points = $team->simulations->avg('score');
        }

        // Sort the teams by their current points and simulation weight score
        $teams = $teams->sortByDesc(function ($team) {
            return $team->stats->points + $team->simulation_weight_score;
        });

        // Calculate the championship rates for each team
        $championshipRates = collect();
        $totalChampionshipRate = 0;
        foreach ($teams as $team) {
            $points = $team->stats->points;
            $remainingMatches = $team->stats->remaining_matches;
            $possiblePoints = $points + ($remainingMatches * 3);
            $championshipRate = 0;

            if ($possiblePoints == $maxPoints) {
                // If this team can win the championship by earning all the remaining points
                $championshipRate = 100;
            } elseif ($remainingMatches == 0) {
                // If this team has played all their matches and cannot win the championship
                $championshipRate = 0;
            } else {
                // Otherwise, calculate the championship rate based on the remaining points
                $remainingPoints = $maxPoints - $possiblePoints;
                $pointsDiff = $points - $teams->last()->stats->points;

                // Use a linear equation to calculate the championship rate
                $championshipRate = ($pointsDiff + $remainingPoints) / ($maxPoints - $teams->last()->stats->points) * 100;
                $championshipRate = min(100, max(0, $championshipRate));
            }

            $championshipRates->put($team->name, $championshipRate);
            $totalChampionshipRate += $championshipRate;
        }

        // Normalize the championship rates so that they sum up to 100%
        if ($totalChampionshipRate > 0) {
            foreach ($championshipRates as $teamName => $championshipRate) {
                $championshipRates->put($teamName, $championshipRate / $totalChampionshipRate * 100);
            }
        }

        return $championshipRates;
    }


    public function predictChampionship()
    {
        // Get current standings from Simulate model
        $standings = Simulation::all();

        $fixtures = Fixture::all();

        // Get total number of weeks remaining in fixtures
        $remainingWeeks = $fixtures->max('week') - $fixtures->where('game_id', '!=', null)->max('week');

        // Calculate remaining games for each team
        $remainingGames = $fixtures->where('game_id', null)->groupBy('home_team_id')->map(function ($teamFixtures) use ($remainingWeeks) {
            return $remainingWeeks - $teamFixtures->count();
        });

        // Calculate the maximum number of points each team can earn in remaining games
        $maxPoints = $remainingGames->map(function ($games, $teamId) use ($standings) {
            $team = $standings->firstWhere('team_id', $teamId);
            $games = $games > 0 ? $games : 1;

            return $team->points + ($games * $team->simulation_weight_score);
        });

        // Calculate the probability of each team winning the championship
        $totalMaxPoints = $maxPoints->sum();
        $probabilities = $maxPoints->map(function ($points) use ($totalMaxPoints) {
            return $points / $totalMaxPoints;
        });

        // Format and return the probabilities
        return $probabilities->map(function ($probability) {
            return round($probability * 100) . '%';
        });
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
