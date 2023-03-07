<?php

namespace Tests\Unit\Models;

use App\Models\Fixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function it_can_get_all_fixtures_grouped_by_week()
    {
        // Create some fixtures in the database
        $fixtures = Fixture::factory()->count(3)->create();

        // Call the fixturesByWeek method on the first fixture
        $result = $fixtures->first()->fixturesByWeek();

        // Assert that the result is an instance of Illuminate\Support\Collection
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);

        // Assert that the result contains the correct number of weeks
        $this->assertCount(1, $result);

        // Assert that the first week in the result contains the correct number of fixtures
        $this->assertCount(3, $result->first());
    }
}
