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
	  
	  $supplied = 0;
	  $issued = 0;
	  foreach ($stocks as $stock){
	  	$supplied += $stock->quantity_supplied;
	  	$issued += $stock->quantity_issued;
	  }
	  $total = $supplied-$issued;
	  return $total;
	}
}