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
            $table->decimal('total_charge', 10, 2)->default(0);
            $table->decimal('total_downpayment', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('status', ['Fully Paid', 'Not Paid'])->default('Not Paid');
            $table->integer('credit_inform_id')->unsigned();
            $table->foreign('credit_inform_id')->references('id')->on('credit_inform')->onDelete('cascade')->onDelete('cascade');
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
