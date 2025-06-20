<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietType extends Model
{
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }

    //Laravel automatycznie tworzy atrybut image_url
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
