<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function scopeHasCustomer($query, Customer $customer)
    {
        $query->whereRaw('ST_Contains(regions.geometry, ?', [$customer]);
    }
}
