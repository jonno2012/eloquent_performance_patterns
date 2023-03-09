<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
