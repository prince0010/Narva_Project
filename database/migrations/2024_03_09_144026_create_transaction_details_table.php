<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cred_inform_id')->unsigned();
            $table->foreign('cred_inform_id')->references('id')->on('credit_inform')->onUpdate('cascade')->onDelete('cascade');
            $table->double('total_charge', 10, 2)->nullable();
            $table->double('total_downpayment', 10, 2)->nullable();
            $table->double('balance', 10, 2)->nullable(); 
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
