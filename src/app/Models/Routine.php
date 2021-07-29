<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function records()
    {
        return $this->hasMany(Record::class, 'routine_id', 'id');
    }

    public function totalRank()
    {
        return $this->belongsTo(Rank::class, 'total_rank_id', 'id');
    }

    public function highestContinuousRank()
    {
        return $this->belongsTo(Rank::class, 'highest_continuous_rank_id', 'id');
    }

    public function recoveryRank()
    {
        return $this->belongsTo(RecoveryRank::class, 'recovery_rank_id', 'id');
    }
}
