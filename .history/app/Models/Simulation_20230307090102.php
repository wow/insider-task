<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Standings table
class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
    ];

    // Calculate simulation weight score
    public function calculateSimulationWeightScore()
    {
        $weightScore = 0;

        $weightScore += $this->won * 3;
        $weightScore += $this->drawn * 1.3;
        $weightScore += $this->lost * -0.9;
        $weightScore += $this->goals_for * 1.3;
        $weightScore += $this->goals_against * -0.6;
        $weightScore += $this->goal_difference * 0.3;
        $weightScore += $this->points * 1.4;

        return $weightScore;
    }

    /**
     * Reset the simulation table
     */
    public function resetSimulation()
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $team->simulation()->update([
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]);
        }
    }

    // Get current standings from the simulated data
    public function getCurrentStandings()
    {
        $standings = Simulation::with('team')->orderBy('points', 'desc')->orderBy('goal_difference', 'desc')->get();

        return $standings;
    }

    // Get current week
    public function getCurrentWeek()
    {
        $currentWeek = Simulation::max('played');

        return $currentWeek;
    }

    // Get current Unplayed week
    public function getCurrentUnplayedWeek()
    {
        $currentWeek = Simulation::min('played') + 1;

        return $currentWeek;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
