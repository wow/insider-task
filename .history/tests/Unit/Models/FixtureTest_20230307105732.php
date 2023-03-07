<?php

namespace Tests\Unit\Models;

use App\Models\Fixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureTest extends TestCase
{
    use RefreshDatabase;

    public function test_fixtures_by_week()
    {
        // Create some fixtures
        Fixture::factory()->create([
            'week' => 1,
            'home_team_id' => 1,
            'away_team_id' => 2,
        ]);
        Fixture::factory()->create([
            'week' => 2,
            'home_team_id' => 3,
            'away_team_id' => 4,
        ]);
        Fixture::factory()->create([
            'week' => 1,
            'home_team_id' => 5,
            'away_team_id' => 6,
        ]);

        // Call the fixturesByWeek method and assert the results
        $fixture = new Fixture();
        $fixturesByWeek = $fixture->fixturesByWeek();
        $this->assertCount(2, $fixturesByWeek);
        $this->assertCount(2, $fixturesByWeek[1]);
        $this->assertCount(1, $fixturesByWeek[2]);
    }

    public function test_games_by_week()
    {
        // Create some fixtures and games
        $fixture1 = Fixture::factory()->create(['week' => 1]);
        $fixture2 = Fixture::factory()->create(['week' => 2]);
        $game1 = $fixture1->games()->create(['week' => 1]);
        $game2 = $fixture1->games()->create(['week' => 1]);
        $game3 = $fixture2->games()->create(['week' => 2]);

        // Call the gamesByWeek method and assert the results
        $gamesWeek1 = $fixture1->gamesByWeek(1)->get();
        $this->assertCount(2, $gamesWeek1);
        $this->assertTrue($gamesWeek1->contains($game1));
        $this->assertTrue($gamesWeek1->contains($game2));

        $gamesWeek2 = $fixture2->gamesByWeek(2)->get();
        $this->assertCount(1, $gamesWeek2);
        $this->assertTrue($gamesWeek2->contains($game3));
    }

    // Add more tests here...
}
