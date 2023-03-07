<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function it_test_predict_championship_returns_teams()
    {
        $service = new PredictionService();
        $teams = $service->predictChampionship();
        $this->assertInstanceOf(Team::class, $teams[0]);
    }

    public function it_tests_get_current_week_returns_int()
    {
        $service = new PredictionService();
        $currentWeek = $service->getCurrentWeek();
        $this->assertIsInt($currentWeek);
    }
}
