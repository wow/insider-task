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
        $numberOfTeams = count($teams);
        $gamesPerWeek = 4;
        $numberOfWeeks = ($numberOfTeams - 1) * 2;
        $fixturesPerWeek = $numberOfTeams / 2;
        $fixtures = [];

        // Generate fixtures for each week
        for ($week = 1; $week <= $numberOfWeeks; $week++) {
            $round = ($week - 1) % ($numberOfTeams - 1);
            $fixturesForWeek = [];

            // Generate fixtures for each team
            for ($fixture = 0; $fixture < $fixturesPerWeek; $fixture++) {
                $homeTeam = ($round + $fixture) % ($numberOfTeams - 1);
                $awayTeam = ($numberOfTeams - 1 - $fixture + $round) % ($numberOfTeams - 1);
                // Last team stays in the same position while the others rotate around it
                if ($fixture == 0) {
                    $awayTeam = $numberOfTeams - 1;
                }
                $fixturesForWeek[] = [
                    'home_team_id' => $teams[$homeTeam]->id,
                    'away_team_id' => $teams[$awayTeam]->id,
                    'week' => $week
                ];
            }

            // Add games for the reversed fixture
            for ($i = 0; $i < count($fixturesForWeek); $i++) {
                $fixturesForWeek[] = [
                    'home_team_id' => $fixturesForWeek[$i]['away_team_id'],
                    'away_team_id' => $fixturesForWeek[$i]['home_team_id'],
                    'week' => $week + $numberOfWeeks
                ];
            }

            $fixtures[] = $fixturesForWeek;
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
