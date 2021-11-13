<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id'
    ];

    public function routine()
    {
        return $this->belongsTo(Routine::class, 'routine_id', 'id');
    }
}
