<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    function generateFixtures() {
        $teams = Team::all();

        // Determine the number of rounds needed to ensure each team plays each other twice
        $numRounds = ($teams->count() - 1) * 2;

        // Shuffle the teams to randomize the fixture order
        $teams = $teams->shuffle();

        // Generate the fixture
        $fixtures = collect();

        for ($round = 1; $round <= $numRounds; $round++) {
            foreach ($teams as $key => $homeTeam) {
                $awayTeamIndex = ($key + $round) % $teams->count();
                $awayTeam = $teams->get($awayTeamIndex);

                // Swap the home and away teams in the second round to ensure each team plays each other twice
                if ($round > $numRounds / 2) {
                    $fixture = new Fixture([
                        'home_team_id' => $awayTeam->id,
                        'away_team_id' => $homeTeam->id,
                        'week' => $round - $numRounds / 2,
                    ]);
                } else {
                    $fixture = new Fixture([
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'week' => $round,
                    ]);
                }

                $fixtures->push($fixture);
            }
        }

        // Save the fixtures to the database
        Fixture::truncate();
        Fixture::insert($fixtures->toArray());

        // Return the fixtures
        // return $fixtures;
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
