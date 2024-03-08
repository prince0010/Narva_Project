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
        Schema::create('credit_names', function (Blueprint $table) {
            $table->increments('id');
            $table->string('credit_name');
            $table->double('downpayment', 10,2)->default(0);
            $table->date('dp_date')->nullable();
            $table->string('invoice_number')->nullable();;
            $table->double('charge', 10, 2)->nullable();
            $table->double('credit_limit', 10, 2)->nullable();
            $table->date('credit_date')->nullable();;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_names');
    }
};
