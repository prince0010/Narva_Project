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
        Schema::create('transaction_details_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cred_inform_id')->unsigned();
            $table->foreign('cred_inform_id')->references('id')->on('credit_inform')->onDelete('cascade');
            $table->text('old_total_downpayment')->nullable();
            $table->text('new_total_downpayment')->nullable();
            $table->text('old_total_charge')->nullable();
            $table->text('new_total_charge')->nullable();
            $table->text('old_balance')->nullable();
            $table->text('new_balance')->nullable();
            $table->text('old_status')->nullable();
            $table->text('new_status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details_logs');
    }
};
