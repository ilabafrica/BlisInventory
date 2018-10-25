<?php

Route::resource('supplier', 'ILabAfrica\Inventory\Controllers\SupplierController');
Route::resource('item', 'ILabAfrica\Inventory\Controllers\ItemController');
Route::resource('stock', 'ILabAfrica\Inventory\Controllers\StockController');
Route::resource('request', 'ILabAfrica\Inventory\Controllers\RequestController');
Route::resource('issueStock', 'ILabAfrica\Inventory\Controllers\StockIssueController');
Route::get('stockDetails/{id}', 'ILabAfrica\Inventory\Controllers\StockController@stockDetails');
Route::get('requestIssue/{id}', 'ILabAfrica\Inventory\Controllers\RequestController@requestIssue');