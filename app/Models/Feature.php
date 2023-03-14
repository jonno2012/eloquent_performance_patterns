<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Feature extends Model
{
    use HasFactory;

    public function scopeOrderByStatus($query, $direction)
    {
        $query->orderBy(DB::raw('
        case
            when status = "Requested" then 1
            when status = "Approved" then 2
            when status = "Completed" then 3
            end
        '), $direction);
    }

    public function scopeOrderByActivity($query, $direction)
    {
        // votes_count * (comments_count * 2)
        $query->orderBy(
            DB::raw('-(votes_count * (comments_count * 2)')
        ,$direction);

        // it isn't possible to create an index for this sub-ranking because it depends on the values of the 2 subqueries
        // If you are working with large datasets on a query like this where you can't apply indexing, this is a
        // possible use-case for 'column caching'/denormalisation, where you would work out the activity ranking and
        // add it as a column
    }
}
