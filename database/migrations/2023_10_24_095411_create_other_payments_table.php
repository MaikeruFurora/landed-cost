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
        Schema::create('other_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_payment_id');
            $table->foreign('invoice_payment_id')->references('id')->on('invoice_payments')->onDelete('cascade')->onUpdate('cascade');
            $table->double('exchangeRate',18,4);
            $table->date('exchangeDate');
            $table->double('quantity',18,4);
            $table->double('dollar',18,4);
            $table->string('particular',10);
            $table->double('totalAmountInPHP',18,4);
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
        Schema::dropIfExists('other_payments');
    }
};
