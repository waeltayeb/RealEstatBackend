<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'price',
        'description',
        'country',
        'type',
        'bedrooms',
        'bathrooms',
        'surface',
        'image',
        'images',
        'user_username',
        'is_available', // Include this if you want to track availability
    ];
}
