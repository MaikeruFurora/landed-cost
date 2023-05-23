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
        Schema::create('lcdpnegos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('landedcost_particular_id');
            $table->foreign('landedcost_particular_id')->references('id')->on('landedcost_particulars')->onDelete('cascade')->onUpdate('cascade');
            $table->double('priceMetricTon',18,4);
            $table->double('percentage',18,4);
            $table->double('amount',18,4);
            $table->double('exchangeRate',18,4);
            $table->date('exchangeRateDate')->nullable();
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
        Schema::dropIfExists('lcdpnegos');
    }
};
