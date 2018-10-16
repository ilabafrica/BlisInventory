<?php

namespace ILabAfrica\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $fillable = ['name', 'unit', 'min', 'max', 'storage_req', 'remarks'];

    public $timestamps = false;
}