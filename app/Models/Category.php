<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'name_ar',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
