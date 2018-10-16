<?php

namespace ILabAfrica\Inventory;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{

    public function boot()
    {
        include __DIR__.'/routes/api.php';

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function register()
    {
        $this->app->make('ILabAfrica\Inventory\Controllers\SupplierController');
        $this->app->make('ILabAfrica\Inventory\Controllers\ItemController');
        $this->app->make('ILabAfrica\Inventory\Controllers\StockController');
        $this->app->make('ILabAfrica\Inventory\Controllers\RequestController');
    }
}
