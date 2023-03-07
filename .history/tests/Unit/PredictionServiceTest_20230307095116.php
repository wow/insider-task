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

    /** @test */
    public function it_predicts_championship()
    {
        // Create teams
        $teamA = Team::factory()->create(['name' => 'Team A']);
        $teamB = Team::factory()->create(['name' => 'Team B']);

        // Create fixtures
        Fixture::factory()->create(['home_team_id' => $teamA->id, 'away_team_id' => $teamB->id, 'week' => 1]);
        Fixture::factory()->create(['home_team_id' => $teamB->id, 'away_team_id' => $teamA->id, 'week' => 1]);

        // Create simulations
        Simulation::factory()->create([
            'team_id' => $teamA->id,
            'played' => 1,
            'won' => 1,
            'points' => 3,
        ]);
        Simulation::factory()->create([
            'team_id' => $teamB->id,
            'played' => 1,
            'drawn' => 1,
            'points' => 1,
        ]);

        // Calculate championship percentages
        $service = new PredictionService();
        $teams = $service->predictChampionship();

        // Assert that the championship percentages add up to 100
        $this->assertEquals(100, collect($teams)->sum('championship_percentage'));
    }

    /** @test */
    public function it_returns_teams_when_no_fixtures_exist()
    {
        // Create teams
        $teamA = Team::factory()->create(['name' => 'Team A']);
        $teamB = Team::factory()->create(['name' => 'Team B']);

        // Calculate championship percentages
        $service = new PredictionService();
        $teams = $service->predictChampionship();

        // Assert that the teams are returned
        $this->assertEquals([$teamA->id, $teamB->id], collect($teams)->pluck('id')->toArray());
    }

    /** @test */
    public function it_returns_teams_when_current_week_is_less_than_last_fixture_week()
    {
        // Create teams
        $teamA = Team::factory()->create(['name' => 'Team A']);
        $teamB = Team::factory()->create(['name' => 'Team B']);

        // Create fixtures
        Fixture::factory()->create(['home_team_id' => $teamA->id, 'away_team_id' => $teamB->id, 'week' => 2]);
        Fixture::factory()->create(['home_team_id' => $teamB->id, 'away_team_id' => $teamA->id, 'week' => 2]);

        // Calculate championship percentages
        $service = new PredictionService();
        $teams = $service->predictChampionship();

        // Assert that the teams are returned
        $this->assertEquals([$teamA->id, $teamB->id], collect($teams)->pluck('id')->toArray());
    }
}
