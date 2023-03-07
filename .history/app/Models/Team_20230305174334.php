<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    // get the games for the team by fixture
    public function gamesByFixture($fixture)
    {
        return $this->hasMany(Game::class)->where('fixture_id', $fixture);
    }
}
