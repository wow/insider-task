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
        // Retrieve all teams
        $teams = Team::all();

        // Check that there are at least 2 teams
        if (count($teams) < 2) {
            return; // Do nothing
        }

        // Calculate the number of fixtures needed
        $num_teams = count($teams);
        $num_home_games = $num_teams - 1;
        $num_away_games = $num_teams - 1;
        $num_weeks = $num_teams - 1;
        $num_fixtures = $num_home_games * $num_away_games * $num_weeks / 2;

        // Create an array of fixture weeks
        $fixture_weeks = range(1, $num_weeks);

        // Shuffle the teams to ensure random fixture generation
        $teams = $teams->shuffle();

        // Generate the fixtures
        $fixtures = collect();
        for ($week = 1; $week <= $num_weeks; $week++) {
            $home_teams = $teams->slice(0, $num_home_games);
            $away_teams = $teams->slice($num_home_games)->reverse();

            foreach ($home_teams as $i => $home_team) {
                $away_team = $away_teams[$i];

                // Create the fixture and save it to the database
                $fixture = new Fixture([
                    'home_team_id' => $home_team->id,
                    'away_team_id' => $away_team->id,
                    'week' => $fixture_weeks[$week - 1]
                ]);
                // $fixture->save();

                // Add the fixture to the fixtures collection
                $fixtures->push($fixture);
            }

            // Rotate the teams for the next round
            $teams = $teams->rotate(-1);
        }

        // Generate the reverse fixtures
        for ($week = 1; $week <= $num_weeks; $week++) {
            $home_teams = $teams->slice($num_home_games)->reverse();
            $away_teams = $teams->slice(0, $num_away_games);

            foreach ($home_teams as $i => $home_team) {
                $away_team = $away_teams[$i];

                // Create the fixture and save it to the database
                $fixture = new Fixture([
                    'home_team_id' => $home_team->id,
                    'away_team_id' => $away_team->id,
                    'week' => $fixture_weeks[$week - 1] + $num_weeks
                ]);
                // $fixture->save();

                // Add the fixture to the fixtures collection
                $fixtures->push($fixture);
            }

            // Rotate the teams for the next round
            $teams = $teams->rotate(-1);
        }

        dd($fixtures);

        // Return the fixtures
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
