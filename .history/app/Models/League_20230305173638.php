<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    // get the fixtures
    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    // get the games
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    // get the teams
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    // get games by fixture
    public function gamesByFixture($fixture)
    {
        return $this->hasMany(Game::class)->where('fixture_id', $fixture);
    }
}
