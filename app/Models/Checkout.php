<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $casts = [
        'borrowed_date' => 'date'
    ];

    // we can use this to get the last checkout of each book to make sure our ordering, using the dynamic relationship,
    // is working properly
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
