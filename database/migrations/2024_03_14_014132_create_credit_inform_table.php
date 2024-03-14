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
        Schema::create('credit_inform', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credit_users_id')->unsigned();
            $table->foreign('credit_users_id')->references('id')->on('credit_users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('credit_date')->nullable();
            $table->string('invoice_number');
            $table->decimal('charge', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_inform');
    }
};
