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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_no',35);
            $table->double('metricTon',18,4);
            $table->double('priceMetricTon',18,4);
            $table->double('amountUSD',18,4);
            $table->double('paidAmountUSD',18,4);
            $table->double('percentage',18,4);
            $table->double('exchangeRate',18,4);
            $table->date('exchangeRateDate');
            $table->double('amountPHP',18,4);
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
        Schema::dropIfExists('contracts');
    }
};
