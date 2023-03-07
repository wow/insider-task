<?php

namespace Tests\Unit\Services;

use App\Models\Fixture;
use App\Models\Simulation;
use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testPredictChampionshipReturnsTeams()
    {
        // Seed the database with example data
        $team = Team::factory()->create();
        $fixture = Fixture::factory()->create();
        $simulation = Simulation::factory()->create([
            'team_id' => $team->id,
        ]);
        $fixture->games()->create([
            'home_team_score' => 2,
            'away_team_score' => 1,
            'home_team_weight_score' => 1.0,
            'away_team_weight_score' => 1.0,
            'played' => 1,
        ]);
        $fixture->games()->create([
            'home_team_score' => 1,
            'away_team_score' => 1,
            'home_team_weight_score' => 1.0,
            'away_team_weight_score' => 1.0,
            'played' => 1,
        ]);

        // Create an instance of the PredictionService
        $predictionService = new PredictionService();

        // Call the predictChampionship() method
        $teams = $predictionService->predictChampionship();

        // Assert that the method returns an array of Team objects
        $this->assertIsArray($teams);
        $this->assertContainsOnlyInstancesOf(Team::class, $teams);

        // Assert that the team has a championship_percentage attribute
        $this->assertObjectHasAttribute('championship_percentage', $teams[0]);
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
