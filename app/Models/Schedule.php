<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'store_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
