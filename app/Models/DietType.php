<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietType extends Model
{
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
