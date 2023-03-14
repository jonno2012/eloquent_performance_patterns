<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Exception;
class Model extends Eloquent
{
    public function getRelationshipFromMethod($name)
    {
        $class = get_class($this);
        throw new Exception("Lazy-loading relationships is not allowed ($class::$name).");
    }
}
