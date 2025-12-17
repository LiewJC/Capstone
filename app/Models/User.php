<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'phone',
        'vehicle_info',
        'otp',
        'email_verified',
        'active_status',
        'selected_store_id',
        
    ];

    public function usedDiscounts()
{
    return $this->belongsToMany(Discount::class, 'discount_user', 'user_id', 'discount_id')->withTimestamps();
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

}
