<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    public function totalRoutines()
    {
        return $this->hasMany(Routine::class, 'total_rank_id', 'id');
    }

    public function continuousRoutines()
    {
        return $this->hasMany(Routine::class, 'highest_continuous_rank_id', 'id');
    }

    public function scopeDefaultId($query) {
        return $query->where('name', '見習い')->value('id');
    }
}
