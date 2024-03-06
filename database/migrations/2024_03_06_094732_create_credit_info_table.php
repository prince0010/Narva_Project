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
        if(!Schema::hasTable('credit_info')){
            Schema::create('credit_info', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('dp_ID')->unsigned();
                $table->foreign('dp_ID')->references('id')->on('downpayment_info')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->date('credit_date');
                $table->string('invoice_number');
                $table->string('charge', 10, 2);
                $table->string('credit_limit', 10, 2);
                // $table->date('dp_date');
                // $table->string('downpayment', 10, 2);
                $table->string('balance', 10, 2);
                $table->string('status');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_info');
    }
};
