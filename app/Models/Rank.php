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

    public function scopeDefaultId($query)
    {
        return $query->where('name', config('const.ranks')[0])->value('id');
    }

    public function scopeName($query, $rank_id)
    {
        return $query->where('id', $rank_id)->value('name');
    }
}
