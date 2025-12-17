<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image_url',
    ];

    public function bookingServices()
{
    return $this->hasMany(\App\Models\BookingService::class, 'service_id');
}

}
