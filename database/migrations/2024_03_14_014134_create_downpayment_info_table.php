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
        Schema::create('downpayment_info', function (Blueprint $table) {
            $table->id();
            $table->integer('credit_inform_id')->unsigned(); // Add the credit_inform_id column
            $table->foreign('credit_inform_id')->references('id')->on('credit_inform')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('downpayment', 10, 2);
            $table->date('dp_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downpayment_info');
    }
};
