<?php

namespace Tests\Unit;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Models\Team;
use App\Models\TeamStats;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FixtureServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_fixtures_for_teams()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();
        $team4 = Team::factory()->create();

        $fixtureService = new FixtureService(new Fixture());

        $fixtureService->generateFixturesForTeams();

        $this->assertEquals(2, Game::count());

        $this->assertDatabaseHas('fixtures', [
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
        ]);

        $this->assertDatabaseHas('fixtures', [
            'home_team_id' => $team2->id,
            'away_team_id' => $team1->id,
        ]);

        $this->assertDatabaseHas('fixtures', [
            'home_team_id' => $team3->id,
            'away_team_id' => $team4->id,
        ]);

        $this->assertDatabaseHas('fixtures', [
            'home_team_id' => $team4->id,
            'away_team_id' => $team3->id,
        ]);
    }
}
