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
        Schema::create('contract_payments', function (Blueprint $table) {
            $table->id();
            $table->string('suppliername',150);
            $table->string('description',200);
            $table->string('reference',100)->nullable();
            $table->double('metricTon',18,4);
            $table->double('priceMetricTon',18,4);
            $table->double('amountUSD',18,4);
            $table->double('paidAmountUSD',18,4);
            $table->string('contract_percent');
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
        Schema::dropIfExists('contract_payments');
    }
};
