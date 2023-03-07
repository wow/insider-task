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

    public function generateFixtures()
    {
        $teams = Team::all();
        // Split the teams into two groups
        $num_teams = count($teams);
        // Create an array to store the fixtures
        $fixtures = [];

        // Loop for the number of rounds, where each team plays every other team once
        for ($round = 0; $round < $num_teams - 1; $round++) {
            // Loop for half the number of teams, and create a fixture for each pair of teams
            for ($i = 0; $i < $num_teams / 2; $i++) {
                $home_team = $teams[$i];
                $away_team = $teams[$num_teams - $i - 1];

                // Create a fixture for the pair of teams
                $fixture = new Fixture();
                $fixture->home_team_id = $home_team->id;
                $fixture->away_team_id = $away_team->id;
                $fixture->week = $round + 1;
                $fixture->save();

                // Add the fixture to the fixtures array
                $fixtures[] = $fixture;
            }

            // Rotate the teams array after each round
            $last_team = $teams->pop();
            $teams->splice(1, 0, $last_team);
        }

        dd($fixtures);

        return $fixtures;
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
