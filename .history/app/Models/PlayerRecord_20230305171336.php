<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'appearances',
        'clean_sheets',
        'goals',
        'assists',
        'created_at',
        'updated_at',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getWeightedPoints()
    {
        return $this->goals * 5 + $this->assists * 3 + $this->clean_sheets * 4;
    }

    public function getWeightedPointsAttribute()
    {
        return $this->goals * 5 + $this->assists * 3 + $this->clean_sheets * 4;
    }

    public function getWeightedPointsPerGameAttribute()
    {
        return $this->getWeightedPoints() / $this->appearances;
    }

    public function getWeightedPointsPer90Attribute()
    {
        return $this->getWeightedPoints() / $this->appearances * 90;
    }

    public function getWeightedPointsPer90PerGameAttribute()
    {
        return $this->getWeightedPointsPer90Attribute() / $this->appearances;
    }
}
