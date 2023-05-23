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
        Schema::create('telegraphic_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->string('transactionNo',12)->nullable();
            $table->date('dateTT',100)->nullable();
            $table->string('branch',100)->nullable();

            $table->boolean('domesticTT')->default(false);
            $table->boolean('foreignTT')->default(false);
            $table->boolean('otherTT')->default(false);
            $table->string('otherTTSpecify',100)->nullable();

            $table->boolean('pddtsDollar')->default(false);
            $table->boolean('rtgsPeso')->default(false);

            $table->string('20_correspondent',100)->nullable();
            $table->string('20_referenceNo',100)->nullable();
            $table->string('20_remittersAccountNo',100)->nullable();
            $table->string('20_invisibleCode',100)->nullable();
            $table->string('20_importersCode',100)->nullable();

            $table->string('32a_valueDate',100)->nullable();
            $table->string('32a_amountAndCurrency',100)->nullable();

            $table->text('50_applicationName')->nullable();
            $table->text('50_presentAddress')->nullable();
            $table->text('50_permanentAddress')->nullable();
            $table->string('50_telephoneNo',100)->nullable();
            $table->string('50_taxIdNo',100)->nullable();
            $table->string('50_faxNo',100)->nullable();
            $table->string('50_otherIdType',100)->nullable();

            $table->string('52_orderingBank',100)->nullable();

            $table->string('56_intermediaryBank',100)->nullable();
            $table->text('56_name')->nullable();
            $table->text('56_address')->nullable();

            $table->text('57_beneficiaryBank')->nullable();
            $table->text('57_name')->nullable();
            $table->text('57_address')->nullable();
            $table->string('57_CountryOfDestination',100)->nullable();

            $table->string('59_beneficiaryAccountNo',100)->nullable();
            $table->text('59_beneficiaryName')->nullable();
            $table->text('59_address')->nullable();
            $table->string('59_industryType',100)->nullable();

            $table->text('70_remittanceInfo')->nullable();

            $table->string('71_chargeFor',10);

            $table->text('72_senderToReceiverInfo')->nullable();

            //Remitter's Other Information
            $table->text('sourceOfFund')->nullable();
            $table->string('industrytype',100)->nullable();
            $table->string('registrationDate',100)->nullable();
            $table->string('birthPlace',100)->nullable();
            $table->string('nationality',100)->nullable();
            $table->string('natureOfWorkOrBusiness',100)->nullable();
            $table->text('purposeOrReason')->nullable();

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
        Schema::dropIfExists('telegraphic_histories');
    }
};
