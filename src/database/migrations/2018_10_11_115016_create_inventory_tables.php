<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('unit');
            $table->string('min');
            $table->string('max');
            $table->string('storage_req');
            $table->string('remarks');
            $table->integer('supplier_id')->unsigned();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });

        Schema::create('request_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
        });

        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('curr_bal');
            $table->integer('lab_section_id')->unsigned();
            $table->integer('tests_done');
            $table->integer('quantity_requested');
            $table->integer('quantity_issued')->nullable()->default('0');
            $table->integer('requested_by');
            $table->integer('issued_by')->nullable();
            $table->integer('status_id')->unsigned()->default('1');
            $table->string('remarks');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('request_status');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('lab_section_id')->references('id')->on('test_type_categories');
            $table->foreign('requested_by')->references('id')->on('users');
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lot_no');
            $table->string('batch_no');
            $table->date('expiry_date');
            $table->string('manufacturer');
            $table->integer('supplier_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('quantity_supplied');
            $table->integer('quantity_issued')->nullable()->default('0');
            $table->integer('cost_per_unit');
            $table->date('date_received');
            $table->string('remarks');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('item_id')->references('id')->on('items');
        });

        Schema::create('stocks_issue_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->string('quantity_issued');
            $table->string('date_issued');
            $table->date('requested_by');
            $table->string('issued_by');
            $table->integer('received_by')->unsigned();
            $table->integer('remarks')->unsigned();
            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('issued_by')->references('id')->on('users');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
