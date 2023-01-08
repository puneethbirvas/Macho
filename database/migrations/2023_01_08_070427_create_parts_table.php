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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customerName')->unsigned();
            $table->foreign('customerName')->references('id')->on('customers')->onDelete('cascade');            $table->string('partId');
            $table->string('partName');
            $table->string('partDescription');
            $table->string('file');
            $table->string('sdCode');
            $table->string('units');
            $table->string('bundleQty');
            $table->string('threshold');
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
        Schema::dropIfExists('parts');
    }
};
