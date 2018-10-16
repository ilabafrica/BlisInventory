<?php

Route::resource('supplier', 'ILabAfrica\Inventory\Controllers\SupplierController');
Route::resource('item', 'ILabAfrica\Inventory\Controllers\ItemController');
Route::resource('stock', 'ILabAfrica\Inventory\Controllers\StockController');
Route::resource('request', 'ILabAfrica\Inventory\Controllers\RequestController');