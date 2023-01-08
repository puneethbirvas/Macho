<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('dispatchDate');
            $table->string('dispatchTime');
            $table->string('type');
            $table->string('deliveryMode');
            $table->bigInteger('customerName')->unsigned();
            $table->foreign('customerName')->references('id')->on('customers')->onDelete('cascade');
            $table->bigInteger('vendorName')->unsigned();
            $table->foreign('vendorName')->references('id')->on('vendors')->onDelete('cascade');
            $table->string('orderDate');
            $table->string('orderReferenceNo');
            $table->string('deliveryType');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
