<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $primaryKey = 'store_id';

    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'latitude',
        'longitude',
        'operation_hours',
        'status',
    ];

    public function schedules()
{
    return $this->hasMany(Schedule::class, 'store_id', 'store_id');
}

}
