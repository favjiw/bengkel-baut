<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['name', 'email','type', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
