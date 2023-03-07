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
        $teamCount = $teams->count();


        $homeTeams = $teams->slice(0, $teamCount / 2);
        $awayTeams = $teams->slice($teamCount / 2)->reverse();

        for ($i = 0; $i < $teamCount - 1; $i++) {
            $week = $i + 1;

            for ($j = 0; $j < $teamCount / 2; $j++) {
                $homeTeam = $homeTeams[$j];
                $awayTeam = $awayTeams[$j];

                $fixture = new Fixture([
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'week' => $week
                ]);

                $fixture->save();
            }

            // Rotate the teams for the next round
            $lastHomeTeam = $homeTeams->pop();
            $awayTeams->push($lastHomeTeam);
            $homeTeams->splice(1, 0, $awayTeams->shift());
        }

        // Save the fixtures to the database

        // Fixture::insert($fixtures->toArray());

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
