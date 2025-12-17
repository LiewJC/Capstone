<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'code',
        'percentage',
        'valid_until',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'discount_user', 'discount_id', 'user_id');
    }

    public function usedByUsers()
{
    return $this->belongsToMany(User::class, 'discount_user', 'discount_id', 'user_id')->withTimestamps();
}


    protected $casts = [
    'valid_until' => 'datetime',
];
}
