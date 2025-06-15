<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function dietTypes()
    {
        return $this->belongsToMany(DietType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
