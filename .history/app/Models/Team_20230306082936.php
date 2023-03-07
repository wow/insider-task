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

    public function generateFixture()
    {
        $teams = Team::all();
        $num_teams = $teams->count();
        $num_rounds = $num_teams - 1;
        $fixtures_per_round = $num_teams / 2;

        // Create the rounds
        $rounds = collect();
        for ($round = 1; $round <= $num_rounds; $round++) {
            $round_fixtures = collect();

            // Create the fixtures for each round
            for ($fixture = 1; $fixture <= $fixtures_per_round; $fixture++) {
                $home_team = $teams[($round + $fixture - 2) % ($num_teams - 1)];
                $away_team = $teams[($num_teams - $fixture + $round) % ($num_teams - 1)];
                if ($fixture == 1) {
                    $away_team = $teams[$num_teams - 1];
                }

                $round_fixtures->push([
                    'home_team_id' => $home_team->id,
                    'away_team_id' => $away_team->id,
                    'week' => $round,
                ]);
            }

            $rounds->push($round_fixtures);
        }

        // Create the knockout fixtures
        $knockout_fixtures = collect();
        $qualified_teams = collect();
        foreach ($rounds as $round_fixtures) {
            foreach ($round_fixtures as $fixture) {
                $home_team = $fixture['home_team_id'];
                $away_team = $fixture['away_team_id'];

                // Add the home and away teams to the list of qualified teams
                $qualified_teams->push($home_team);
                $qualified_teams->push($away_team);

                // Create the first leg fixture
                $knockout_fixtures->push([
                    'home_team_id' => $home_team->id,
                    'away_team_id' => $away_team->id,
                    'week' => $num_rounds + 1,
                ]);

                // Create the second leg fixture
                $knockout_fixtures->push([
                    'home_team_id' => $away_team->id,
                    'away_team_id' => $home_team->id,
                    'week' => $num_rounds + 2,
                ]);
            }
        }

        // Remove duplicates from the list of qualified teams
        $qualified_teams = $qualified_teams->unique();

        // Add fixtures to the database
        DB::beginTransaction();

        try {
            foreach ($rounds as $round_fixtures) {
                foreach ($round_fixtures as $fixture) {
                    Fixture::create($fixture);
                }
            }

            foreach ($knockout_fixtures as $fixture) {
                Fixture::create($fixture);
            }

            DB::commit();

            return response()->json(['message' => 'Fixtures generated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
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
