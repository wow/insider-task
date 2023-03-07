<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'home_team_score',
        'away_team_score',
        'away_team_weight_score',
        'home_team_weight_score',
        'played',
    ];

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }
}
