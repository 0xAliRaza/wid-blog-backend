<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    function isEmpty() {
        return empty($this->getAttribute('id'));
    }
}
