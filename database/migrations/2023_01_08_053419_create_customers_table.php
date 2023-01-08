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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customerCode');
            $table->string('gstNumber')->unique();
            $table->string('customerName');
            $table->string('billingAddress');
            $table->string('shippingAddress');
            $table->string('contactPersonName')->unique();
            $table->string('primaryContactNumber');
            $table->string('secondaryContactNumber');
            $table->string('email');
            $table->string('remark');
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
        Schema::dropIfExists('customers');
    }
};
