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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_payment_id');
            $table->foreign('contract_payment_id')->references('id')->on('contract_payments')->onDelete('cascade')->onUpdate('cascade');
            $table->string('reference',100)->nullable();
            $table->string('invoiceno',100)->nullable();
            $table->double('metricTon',18,4);
            $table->double('priceMetricTon',18,4);
            $table->double('amountUSD',18,4);
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
        Schema::dropIfExists('invoice_payments');
    }
};
