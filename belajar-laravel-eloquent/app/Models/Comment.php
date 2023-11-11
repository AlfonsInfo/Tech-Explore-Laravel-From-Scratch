<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = ["id"]; 
    protected $primaryKey = 'id'; 
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;


    //* default value saat object dibuat
    public $attributes = [
        "title" => "sample title",
        "title" => "sample comment",
    ];
}
