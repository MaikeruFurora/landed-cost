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
        Schema::create('bank_histories', function (Blueprint $table) {
            $table->id();
            $table->string('transactionNo',12)->nullable();
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->string('attention',50);
            $table->string('subject',100);
            $table->string('types',3);
            $table->boolean('isManual')->nullable();
            $table->decimal('amount',18,4)->nullable();
            $table->string('toName',50)->nullable();
            $table->string('toBankName',50)->nullable();
            $table->string('toBranchName',50)->nullable();
            $table->string('toAccountNo',50)->nullable();
            $table->double('exchangeRate',18,4);
            $table->date('exchangeRateDate')->nullable();
            $table->text('purpose');
            $table->date('dated_at')->nullable();
            $table->date('posted_at')->nullable();
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
        Schema::dropIfExists('bank_histories');
    }
};
