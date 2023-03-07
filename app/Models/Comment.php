<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function isAuthor()
    {
        return $this->feature->comments->first()->user_id === $this->user_id;
    }
}
