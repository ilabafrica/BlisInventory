<?php

namespace ILabAfrica\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $fillable = ['name', 'unit', 'min', 'max', 'storage_req', 'remarks'];

    public $timestamps = false;

    public function stock() {
	  return $this->hasMany('ILabAfrica\Inventory\Models\Stock');
	}

	public function getStockValueAttribute() {
	  $stocks = $this->stock()->get();
	  $total =0;
	  foreach ($stocks as $stock){
	  	$total += $stock->quantity_supplied;
	  }
	  return $total;
	}
}