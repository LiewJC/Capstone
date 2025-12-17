<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
protected $table = 'feedbacks';

    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'user_id',
        'booking_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
{
    return $this->belongsTo(Booking::class, 'booking_id');
}

}
