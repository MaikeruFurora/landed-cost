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
        Schema::create('freight_payments', function (Blueprint $table) {
            $table->id();
            $table->string('suppliername',150);
            $table->string('description',200);
            $table->string('reference',100)->nullable();
            $table->double('exchangeRate',18,4);
            $table->date('exchangeDate');
            $table->double('quantity',18,4);
            $table->double('dollar',18,4);
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
        Schema::dropIfExists('freight_payments');
    }
};
