<?php

namespace ILabAfrica\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{	
	protected $table = 'request_status';

    public $fillable = ['code', 'name'];

    public $timestamps = false;
}