<?php

namespace Tests\Unit;

use App\Models\Fixture;
use App\Models\Simulation;
use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;


class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testPredictChampionshipReturnsTeamsWithChampionshipPercentageAttribute()
{
    // Create some teams and fixtures
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();
    $teamC = Team::factory()->create();
    $fixtureA = Fixture::factory()->create(['home_team_id' => $teamA->id, 'away_team_id' => $teamB->id, 'week' => 1]);
    $fixtureB = Fixture::factory()->create(['home_team_id' => $teamB->id, 'away_team_id' => $teamC->id, 'week' => 2]);
    $fixtureC = Fixture::factory()->create(['home_team_id' => $teamC->id, 'away_team_id' => $teamA->id, 'week' => 3]);

    // Create some simulations for the teams
    $simulationA = Simulation::factory()->create(['team_id' => $teamA->id, 'played' => 2, 'won' => 1, 'lost' => 1, 'drawn' => 0]);
    $simulationB = Simulation::factory()->create(['team_id' => $teamB->id, 'played' => 2, 'won' => 1, 'lost' => 1, 'drawn' => 0]);
    $simulationC = Simulation::factory()->create(['team_id' => $teamC->id, 'played' => 2, 'won' => 1, 'lost' => 1, 'drawn' => 0]);

    // Create the PredictionService and call the predictChampionship method
    $service = new PredictionService();
    $teams = $service->predictChampionship();

    // Check that each team has a championship_percentage attribute
    foreach ($teams as $team) {
        $this->assertObjectHasAttribute('championship_percentage', $team);
    }
}

    public function testGetCurrentWeekReturnsInt()
    {
        // Seed the database with example data
        Simulation::factory()->create([
            'played' => 2,
        ]);

        // Create an instance of the PredictionService
        $predictionService = new PredictionService();

        // Call the getCurrentWeek() method
        $currentWeek = $predictionService->getCurrentWeek();

        // Assert that the method returns an integer
        $this->assertIsInt($currentWeek);
    }
}
