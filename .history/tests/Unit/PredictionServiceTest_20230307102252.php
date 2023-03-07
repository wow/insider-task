<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testPredictChampionshipReturnsTeams()
    {
        $service = new PredictionService();
        $teams = $service->predictChampionship();
        $this->assertInstanceOf(Team::class, $teams[0]);
    }

    public function testGetCurrentWeekReturnsInt()
    {
        $service = new PredictionService();
        $currentWeek = $service->getCurrentWeek();
        $this->assertIsInt($currentWeek);
    }
}
