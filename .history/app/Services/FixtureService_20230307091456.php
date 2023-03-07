<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Models\Team;

/**
 * Class FixtureService
 */
class FixtureService
{
    public function __construct(Private Fixture $fixture)
    {
    }

    /**
     * Generate fixtures for the teams
     *
     * @return void
     */
    public function generateFixturesForTeams()
    {
        $this->resetDataForNewFixtures();

        $teams = Team::all();
        // $teams = $teams->shuffle();
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
                $this->createFixtures($homeTeam, $awayTeam, $round, $rounds);
            }
        }
    }

    // create fixtures for the home and away teams
    private function createFixtures($homeTeam, $awayTeam, $round, $rounds)
    {
        // Generate fixtures for the home and away teams
        $firstFixture = $this->fixture->create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'week' => $round + 1
        ]);

        Game::create([
            'fixture_id' => $firstFixture->id,
        ]);

        $secondFixture = $this->fixture->create([
            'home_team_id' => $awayTeam->id,
            'away_team_id' => $homeTeam->id,
            'week' => $round + $rounds + 1
        ]);

        Game::create([
            'fixture_id' => $secondFixture->id,
        ]);
    }

    /**
     * Reset all fixtures, games and team stats
     *
     * @return void
     */
    private function resetDataForNewFixtures()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->truncate();
        Game::truncate();
        TeamStats::truncate();
        Simulation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Artisan::call('db:seed', ['--class' => 'TeamStatsSeeder']);
        Artisan::call('db:seed', ['--class' => 'SimulationSeeder']);
    }
}
