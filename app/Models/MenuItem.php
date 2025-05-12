<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;



class MenuItem extends Model
{

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'is_available',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
