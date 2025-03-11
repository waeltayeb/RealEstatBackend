<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
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
        'is_available',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_username', 'username');
    }
}
