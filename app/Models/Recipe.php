<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'instructions',
        'preparation_time',
        'image',
        'user_id',
        'recipe_id',
        'content',
        'rating'
    ];

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot('quantity', 'unit', 'is_visible')
            ->withTimestamps();
    }
}
