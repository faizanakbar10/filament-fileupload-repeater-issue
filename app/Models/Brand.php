<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'title',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
