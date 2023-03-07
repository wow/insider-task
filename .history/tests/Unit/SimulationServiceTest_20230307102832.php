<?php

namespace Tests\Unit;

use App\Models\Fixture;
use App\Models\Game;
use App\Models\Simulation;
use App\Services\FixtureService;
use App\Services\SimulationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulationServiceTest extends TestCase
{
    use RefreshDatabase;

    private SimulationService $simulationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simulationService = app(SimulationService::class);
    }

    /** @test */
    public function simulate_by_week_method_simulates_all_fixtures_for_given_week()
    {
        // Arrange
        Fixture::factory()->create(['week' => 1]);
        Fixture::factory()->create(['week' => 1]);
        Fixture::factory()->create(['week' => 2]);
        Fixture::factory()->create(['week' => 2]);

        // Act
        $this->simulationService->simulateByWeek(1);

        // Assert
        $this->assertCount(2, Simulation::all());
        $this->assertGreaterThan(0, Fixture::first()->games()->first()->home_team_score);
    }

    /** @test */
    public function simulate_all_games_method_simulates_all_fixtures_for_all_weeks()
    {
        // Arrange
        Fixture::factory()->create(['week' => 1]);
        Fixture::factory()->create(['week' => 2]);

        // Act
        $this->simulationService->simulateAllGames();

        // Assert
        $this->assertCount(2, Simulation::all());
        $this->assertGreaterThan(0, Fixture::first()->games()->first()->home_team_score);
        $this->assertGreaterThan(0, Fixture::find(2)->games()->first()->home_team_score);
    }
}
