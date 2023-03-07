<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

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

    public function generateFixtures()
    {
        $teams = Team::all();
        $numTeams = $teams->count();

        // Create an array of team ids
        $teamIds = [];
        foreach ($teams as $team) {
            $teamIds[] = $team->id;
        }

        // Create an array of fixtures
        $fixtures = [];
        $numWeeks = $numTeams - 1;
        for ($i = 0; $i < $numWeeks; $i++) {
            for ($j = 0; $j < $numTeams / 2; $j++) {
                $homeTeam = $teamIds[$j];
                $awayTeam = $teamIds[$numTeams - $j - 1];
                if ($homeTeam != $numTeams && $awayTeam != $numTeams) {
                    $fixtures[] = [
                        'home_team_id' => $homeTeam,
                        'away_team_id' => $awayTeam,
                        'week' => $i + 1
                    ];
                }
            }
            // Rotate the teams
            $lastTeam = array_pop($teamIds);
            array_splice($teamIds, 1, 0, $lastTeam);
        }

        dd($fixtures);
    }




    // Generate fixtures for teams
    public function generateFixtures2()
    {
        $teams = Team::all()->toArray();
        $num_teams = count($teams);

        // Generate a list of rounds
        $rounds = [];
        for ($i = 1; $i < $num_teams; $i++) {
            $round = [];
            for ($j = 0; $j < $num_teams/2; $j++) {
                $match = [$teams[$j], $teams[$num_teams-1-$j]];
                array_push($round, $match);
            }
            array_push($rounds, $round);

            // Rotate the teams in the array
            array_splice($teams, 1, 0, array_splice($teams, $num_teams-2, 1));
        }

        // Generate fixtures for each round
        $fixtures = [];
        $week = 1;
        foreach ($rounds as $round) {
            foreach ($round as $match) {
                $home_team = $match[0];
                $away_team = $match[1];
                // Save the fixture to the database
                Fixture::create([
                    'home_team_id' => $home_team['id'],
                    'away_team_id' => $away_team['id'],
                    'week' => $week,
                ]);
            }
            $week++;
        }

        // Repeat the rounds and switch the home and away teams
        foreach ($rounds as $round) {
            foreach ($round as $match) {
                $home_team = $match[1];
                $away_team = $match[0];
                // Save the fixture to the database
                Fixture::create([
                    'home_team_id' => $home_team['id'],
                    'away_team_id' => $away_team['id'],
                    'week' => $week,
                ]);
            }
            $week++;
        }
    }
}
