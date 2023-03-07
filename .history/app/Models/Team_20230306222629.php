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

    // get teamStats for the team
    public function teamStats()
    {
        return $this->hasOne(TeamStats::class);
    }

    // // get simulation for the team
    // public function simulation()
    // {
    //     return $this->hasOne(Simulation::class);
    // }
}
