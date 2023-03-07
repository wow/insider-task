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
        }

        // Calculate championship prediction for each team
        $totalPoints = $teams->sum('points');
        $teams->each(function ($team) use ($totalPoints) {
            $team->championshipPrediction = $team->points / $totalPoints;
        });

        return $teams;
    }
}
