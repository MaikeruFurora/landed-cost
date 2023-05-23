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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('companyname',100);
            $table->boolean('companyStatus')->default(true);
            $table->text('companyAddress')->nullable();
            $table->string('tinNo',15)->nullable();
            $table->date('registrationDate',15)->nullable();
            $table->boolean('companyStatus')->default(true);
            $table->string('acronym',10)->nullable();
            
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
        Schema::dropIfExists('companies');
    }
};
