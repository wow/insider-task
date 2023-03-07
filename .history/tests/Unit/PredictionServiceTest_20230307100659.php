<?php

namespace Tests\Unit\Services;

use App\Models\Fixture;
use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test championship prediction with no fixtures.
     *
     * @return void
     */
    public function testPredictChampionshipWithNoFixtures()
    {
        $teams = Team::factory()->count(5)->create();

        $service = new PredictionService();
        $predicted = $service->predictChampionship();

        $this->assertCount(5, $predicted);

        foreach ($teams as $team) {
            $this->assertEquals($team->id, $predicted->find($team->id)->id);
            $this->assertEquals(0, $predicted->find($team->id)->championship_percentage);
        }
    }

    /**
     * Test championship prediction with fixtures.
     *
     * @return void
     */
    public function testPredictChampionshipWithFixtures()
    {
        $teams = Team::factory()->count(5)->create();
        Fixture::factory()->count(12)->create();

        $service = new PredictionService();
        $predicted = $service->predictChampionship();

        $this->assertCount(5, $predicted);

        $totalPercentage = 0;
        foreach ($predicted as $team) {
            $this->assertEquals($team->id, $predicted->find($team->id)->id);
            $this->assertGreaterThanOrEqual(0, $team->championship_percentage);
            $this->assertLessThanOrEqual(100, $team->championship_percentage);
            $totalPercentage += $team->championship_percentage;
        }

        $this->assertEquals(100, $totalPercentage);
    }
}
