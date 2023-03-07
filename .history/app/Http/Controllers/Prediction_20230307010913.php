<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Models\Team;

class ChampionshipPredictionController extends Controller
{
    /**
     * Calculate the championship prediction for each team
     *
     * @return array
     */
    public function predict()
    {
        // Get the last 3 weeks of fixtures
        $fixtures = Fixture::orderBy('week', 'desc')->take(3)->get();

        $prediction = [];

        // Loop through all the teams and calculate their championship prediction
        foreach (Team::all() as $team) {
            $points = 0;
            $played = 0;

            // Loop through all the fixtures to get the team's points and played games
            foreach ($fixtures as $fixture) {
                $game = $fixture->games()->where(function ($query) use ($team) {
                    $query->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id);
                })->first();

                if ($game) {
                    $played++;
                    $points += $game->pointsForTeam($team);
                }
            }

            // Calculate the team's remaining matches and points
            $remainingMatches = Simulation::where('team_id', $team->id)->sum('played') - $played;
            $remainingPoints = Simulation::where('team_id', $team->id)->sum('points') - $points;

            // Calculate the simulation weight score
            $simulationWeightScore = $this->calculateSimulationWeightScore($team, $remainingMatches, $remainingPoints);

            // Calculate the championship prediction percentage
            if ($played + $remainingMatches == 0) {
                $prediction[$team->name] = 0;
            } else {
                $prediction[$team->name] = round($points / ($played * 3) * 100 * $simulationWeightScore);
            }
        }

        // Sort the prediction array by descending order
        arsort($prediction);

        return $prediction;
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
