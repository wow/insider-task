<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Simulation;
use App\Models\Team;

/**
 * Class PredictionService
 */
class PredictionService
{
    /**
     * @return Team[]
     */
    public function predictChampionship()
    {
        // Get the last 3 weeks of fixtures
        $fixtures = Fixture::orderBy('week', 'desc')->take(3)->get();

        // Get the teams
        $teams = Team::all();
        if ($fixtures->isEmpty()) {
            return $teams;
        }

        $fixtures->last();
        if ($this->getCurrentWeek() < $fixtures->last()->first()->week) {
            return $teams;
        }

        // Calculate the points and remaining matches for each team
        $teamPoints = [];
        $remainingMatches = [];
        foreach ($teams as $team) {
            $teamPoints[$team->id] = $team->simulation()->sum('points');
            $remainingMatches[$team->id] = $fixtures->filter(function ($fixture) use ($team) {
                return $fixture->home_team_id == $team->id || $fixture->away_team_id == $team->id;
            })->count();
        }

        // Calculate the championship percentages
        $totalPoints = array_sum($teamPoints) > 0 ? array_sum($teamPoints) : 1;
        $championshipPercentages = [];
        foreach ($teams as $team) {
            $simulationWeightScore = $team->simulation->calculateSimulationWeightScore();

            $points = $teamPoints[$team->id];
            $remaining = $remainingMatches[$team->id];
            $opponentsPoints = 0;
            foreach ($fixtures as $fixture) {
                if ($fixture->home_team_id == $team->id) {
                    $opponentsPoints += $teamPoints[$fixture->away_team_id];
                } elseif ($fixture->away_team_id == $team->id) {
                    $opponentsPoints += $teamPoints[$fixture->home_team_id];
                }

            }
            $opponentsAveragePoints = ($opponentsPoints / $remainingMatches[$team->id]) ?? 0;
            $championshipPercentage = (($points + ($remaining * $opponentsAveragePoints)) / $totalPoints) * $simulationWeightScore;
            $championshipPercentages[$team->id] = $championshipPercentage;
        }

        // Normalize the championship percentages so they add up to 100%
        $sum = array_sum($championshipPercentages);
        foreach ($teams as $team) {
            $championshipPercentages[$team->id] = ($sum > 0) ? round(($championshipPercentages[$team->id] / $sum) * 100, 0) : 0;
            $team->championship_percentage = $championshipPercentages[$team->id];
        }

        return $teams;
    }


    /**
     * @return int
     */
    public function getCurrentWeek()
    {
        $currentWeek = Simulation::max('played');

        return $currentWeek;
    }
}
