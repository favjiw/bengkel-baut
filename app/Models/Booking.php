<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['name', 'phone','type', 'category_id', 'timestart', 'timeend'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
