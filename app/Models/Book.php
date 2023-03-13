<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsToMany(User::class, 'users')
            ->using(Checkout::class)
            ->withPivot('borrowed_date');
    }
}
