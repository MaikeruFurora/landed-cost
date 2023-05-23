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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->integer('pono');
            $table->string('itemcode',50)->nullable();
            $table->string('cardname',200)->nullable();
            $table->string('cardcode',50)->nullable();
            $table->double('actualQtyKLS',18,3)->nullable();
            $table->double('actualQtyMT',18,3)->nullable();
            $table->string('vessel',200)->nullable();
            $table->string('description',200);
            $table->string('invoiceno',50);
            $table->string('broker',200)->nullable();
            $table->integer('weight')->nullable();
            $table->double('quantity',18,3)->nullable();
            $table->double('qtykls',18,3)->nullable();
            $table->double('qtymt',18,3)->nullable();
            $table->integer('fcl')->nullable();
            $table->string('suppliername',200)->nullable();
            $table->datetime('posted_at')->nullable();
            $table->datetime('doc_date')->nullable();
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
        Schema::dropIfExists('details');
    }
};
