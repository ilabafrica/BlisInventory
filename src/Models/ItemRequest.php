<?php

namespace ILabAfrica\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{	
	protected $table = 'requests';

    public $fillable = ['item_id', 'curr_bal', 'lab_section_id', 'tests_done', 'quantity_requested', 'remarks'];

    public $timestamps = true;

    public function item()
    {
        return $this->hasOne('ILabAfrica\Inventory\Models\Item', 'id', 'item_id');
    }

    public function lab()
    {
        return $this->hasOne('App\Models\TestTypeCategory', 'id', 'lab_section_id');
    }
}