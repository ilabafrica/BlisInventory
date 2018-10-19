<?php

namespace ILabAfrica\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $fillable = ['lot_no', 'batch_no', 'expiry_date', 'manufacturer', 'supplier_id', 'quantity_supplied', 'cost_per_unit', 'date_received', 'remarks'];

    public $timestamps = false;

    public function request() {
	  return $this->hasManyThrough('ILabAfrica\Inventory\Models\ItemRequest', 'ILabAfrica\Inventory\Models\Item', 'id', 'item_id', 'id', 'id');
	}
}