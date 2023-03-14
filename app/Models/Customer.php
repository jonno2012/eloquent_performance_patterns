<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;

class Customer extends Model
{
    use HasFactory;

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->is_owner) {
            return;
        }

        $query->where('sales_rep_id', $user->id);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeInRegions($query, Region $region)
    {
        $query->whereRaw('ST_Contains(?, customers.location)', [$region->geometry]);
    }
}
