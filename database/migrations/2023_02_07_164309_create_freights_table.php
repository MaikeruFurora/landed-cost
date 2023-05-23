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
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('landedcost_particular_id');
            $table->foreign('landedcost_particular_id')->references('id')->on('landedcost_particulars')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('dollarRate',18,4);
            $table->decimal('exhangeRate',18,4);
            $table->string('exhangeRateDate',15);
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
        Schema::dropIfExists('freights');
    }
};
