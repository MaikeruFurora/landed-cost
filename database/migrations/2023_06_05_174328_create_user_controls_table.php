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
        Schema::create('user_controls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_control')->nullable();
            $table->foreign('sub_control')->references('id')->on('user_controls');
            $table->string('code',5);
            $table->string('name',30);
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
        Schema::dropIfExists('user_controls');
    }
};
