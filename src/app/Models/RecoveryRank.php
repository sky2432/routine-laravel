<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryRank extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function routines()
    {
        return $this->hasMany(Routine::class, 'recovery_rank_id', 'id');
    }

    public function scopeDefaultId($query) {
        return $query->where('name', '見習い')->value('id');
    }
}
