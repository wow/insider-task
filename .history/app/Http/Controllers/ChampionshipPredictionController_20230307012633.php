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
            $team->simulationWeightScore = $simulator->calculateSimulationWeightScore($team, $remainingFixtures);
        }

        // Calculate championship prediction for each team
        $totalPoints = $teams->sum('points');
        $teams->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $teams;
    }

    /**
     * Calculate the simulation weight score for the team
     *
     * @param Team $team
     * @param int $remainingMatches
     * @param int $remainingPoints
     * @return float
     */
    private function calculateSimulationWeightScore($team, $remainingMatches, $remainingPoints)
    {
        $playedMatches = Simulation::where('team_id', $team->id)->sum('played');

        if ($playedMatches == 0 || $remainingMatches == 0) {
            return 1;
        }

        $averagePointsPerMatch = ($remainingPoints / $remainingMatches) / 3;
        $averagePointsPerMatch *= $playedMatches;

        if ($averagePointsPerMatch > $team->average_points) {
            return 1.2;
        } else {
            return 0.8;
        }
    }
}
