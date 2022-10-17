<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
