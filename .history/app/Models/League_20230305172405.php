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

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
