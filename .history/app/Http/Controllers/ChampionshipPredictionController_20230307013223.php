<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Models\Team;

class ChampionshipPredictionController extends Controller
{
    public function predictChampionship()
    {
        $simulator = new Simulation();

        // Get current standings from the simulated data
        $teams = $simulator->getCurrentStandings();

        // Get remaining fixtures and calculate the simulation weight score for each team
        $remainingFixtures = Fixture::where('week', '>', $simulator->getCurrentWeek())->get();
        foreach ($teams as $team) {
            $team->simulationWeightScore = $simulator->calculateSimulationWeightScore();
            $team->simulationWeightScore += $this->calculateSimulationWeightScore($team, $remainingFixtures->count());
        }

        // Calculate championship prediction for each team
        $totalPoints = $teams->sum('points');
        $teams->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $teams;
    }

    public function calculateSimulationWeightScore($team, $remainingFixtures)
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($remainingFixtures as $fixture) {
            $homeTeam = $fixture->homeTeam;
            $awayTeam = $fixture->awayTeam;

            if ($homeTeam->id == $team->id || $awayTeam->id == $team->id) {
                $homeTeamStats = $homeTeam->stats;
                $awayTeamStats = $awayTeam->stats;

                $homeTeamWeight = $this->calculateTeamWeight($homeTeamStats);
                $awayTeamWeight = $this->calculateTeamWeight($awayTeamStats);

                $totalWeight += $homeTeamWeight + $awayTeamWeight;

                if ($homeTeam->id == $team->id) {
                    $totalScore += $homeTeamStats->points * $homeTeamWeight;
                    $totalScore += $awayTeamStats->points * $awayTeamWeight;
                } else {
                    $totalScore += $awayTeamStats->points * $awayTeamWeight;
                    $totalScore += $homeTeamStats->points * $homeTeamWeight;
                }
            }
        }

        return $totalScore / $totalWeight;
    }
}
