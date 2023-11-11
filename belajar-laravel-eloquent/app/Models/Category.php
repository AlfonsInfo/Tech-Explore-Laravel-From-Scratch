<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected $table = 'categories'; // represent categories on database
    protected $primaryKey = 'id'; //represent column primary key
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, "category_id", "id");
    }

    // protected static function booted(){
    //     parent::booted();
    //     self::addGlobalScope(new IsActiveScope());
    // }

    public function cheapestProduct() : HasOne{
        return $this->hasOne(Products::class, 'category_id', 'id')->oldest('price');
    }


    
    public function mostExpensiveProduct() : HasOne{
        return $this->hasOne(Products::class, 'category_id', 'id')->oldest('price');
    }
}
