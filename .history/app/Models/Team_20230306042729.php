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

    // get team score from all players weight from PlayerStats model
    public function getTeamScoreByPlayers()
    {
        $teamScore = 0;

        foreach ($this->players() as $player) {
            $teamScore += $player->playerStats()->getPlayerScore($player->id);
        }

        return $teamScore;
    }

    // Generate fixtures for teams
    public function generateFixtures()
    {
        $teams = Team::all(); // Get all the teams

        // Generate fixtures
        $fixtures = [];

        for ($i = 0; $i < count($teams); $i++) {
            for ($j = $i + 1; $j < count($teams); $j++) {
                // Create home and away fixtures
                $home = ['team_id' => $teams[$i]->id, 'week' => $j];
                $away = ['team_id' => $teams[$j]->id, 'week' => $j];

                // Add fixtures to the fixtures array
                $fixtures[] = array('home_team_id' => $home['team_id'], 'away_team_id' => $away['team_id'], 'week' => $home['week']);
                $fixtures[] = array('home_team_id' => $away['team_id'], 'away_team_id' => $home['team_id'], 'week' => $away['week']);
            }
        }

        // Save fixtures to the database
        foreach ($fixtures as $fixture) {
            Fixture::create($fixture);
        }


        // $teams = Team::all();
        // $weeks = 4;
        // $fixtures = [];

        // for ($i = 0; $i < $weeks; $i++) {
        //     $fixtures[$i] = [];
        // }

        // $teamsCount = $teams->count();

        // for ($i = 0; $i < $teamsCount; $i++) {
        //     for ($j = 0; $j < $teamsCount; $j++) {
        //         if ($i != $j) {
        //             $fixtures[$i][] = [
        //                 'home_team_id' => $teams[$i]->id,
        //                 'away_team_id' => $teams[$j]->id,
        //                 'week' => $i + 1,
        //             ];
        //         }
        //     }
        // }

        // foreach ($fixtures as $fixture) {
        //     foreach ($fixture as $game) {
        //         Fixture::create($game);
        //     }
        // }
    }
}
