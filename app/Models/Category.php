<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'price', 'description', 'minute'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
