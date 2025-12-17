<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'user_id',
        'store_id',
        'datetime',
        'timeStart',
        'timeEnd',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function feedback()
{
    return $this->hasOne(Feedback::class, 'booking_id');
}

public function payment()
{
    return $this->hasOne(Payment::class, 'booking_id');
}


    public function bookingServices()
{
    return $this->hasMany(BookingService::class, 'booking_id');
}

}
