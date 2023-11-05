<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // represent categories on database
    protected $primaryKey = 'id'; //represent column primary key
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

}
