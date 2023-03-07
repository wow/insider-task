<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team_id',
        'number',
        'position',
        'created_at',
        'updated_at',
    ];

    // get the team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // get PlayerRecord
    public function playerRecord()
    {
        return $this->hasOne(PlayerRecord::class);
    }

    // get PlayerStats
    public function playerStats()
    {
        return $this->hasOne(PlayerStats::class);
    }
}
