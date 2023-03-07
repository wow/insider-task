<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    // get the games for the team by week from the fixture
    public function gamesByWeek($week)
    {
        return $this->hasManyThrough(Game::class, Fixture::class)
            ->where('week', $week);
    }

    /**
     * Generate fixtures for the teams
     *
     * @return void
     */
    public function generateFixtures()
    {
        $teams = Team::all();
        $teamCount = $teams->count();

        $rounds = $teamCount - 1;
        $matchesPerRound = $teamCount / 2;

        for ($round = 0; $round < $rounds; $round++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $homeTeamIndex = ($round + $match) % ($teamCount - 1);
                $awayTeamIndex = ($teamCount - 1 - $match + $round) % ($teamCount - 1);

                // Last team stays in the same position while the others rotate
                if ($match == 0) {
                    $awayTeamIndex = $teamCount - 1;
                }

                $homeTeam = $teams[$homeTeamIndex];
                $awayTeam = $teams[$awayTeamIndex];

                // Generate fixtures for the home and away teams
                Fixture::create([
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'week' => $round + 1
                ]);

                Fixture::create([
                    'home_team_id' => $awayTeam->id,
                    'away_team_id' => $homeTeam->id,
                    'week' => $round + $rounds + 1
                ]);
            }
        }
    }
}
