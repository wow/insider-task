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
        $teams = Team::all()->toArray();

        // Make sure there are at least two teams
        if (count($teams) < 2) {
            throw new \InvalidArgumentException('There must be at least two teams to generate fixtures');
        }

        // Initialize an empty fixtures array
        $fixtures = [];

        // Get the number of teams and the number of rounds required for a round-robin tournament
        $numTeams = count($teams);
        $numRounds = ($numTeams - 1) * 2;

        // Create a list of team IDs
        $teamIds = array_column($teams, 'id');

        // Loop through each round and generate fixtures
        for ($round = 1; $round <= $numRounds; $round++) {
            $roundFixtures = [];

            // Determine the home and away teams for each match in the round
            for ($match = 1; $match <= $numTeams / 2; $match++) {
                $homeTeam = $teamIds[($match + $round - 2) % ($numTeams - 1)];
                $awayTeam = $teamIds[($numTeams - $match + $round) % ($numTeams - 1)];

                // If there are an odd number of teams, fix the last team to play at home for each round
                if ($numTeams % 2 == 1 && $match == 1) {
                    $awayTeam = $teamIds[$numTeams - 1];
                }

                // Add the fixture to the round fixtures array
                $roundFixtures[] = [
                    'home_team_id' => $homeTeam,
                    'away_team_id' => $awayTeam,
                    'week' => $round
                ];
            }

            // Add the round fixtures to the main fixtures array
            $fixtures[] = $roundFixtures;
        }

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
