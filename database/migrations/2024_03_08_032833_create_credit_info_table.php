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
                $table->integer('credit_names_id')->unsigned();
                $table->foreign('credit_names_id')->references('id')->on('credit_names')->onUpdate('cascade')->onUpdate('cascade');
                $table->double('total_charge', 10, 2)->nullable();
                $table->double('total_downpayment', 10, 2)->nullable();
                // $table->date('dp_date');
                // $table->string('downpayment', 10, 2);
                $table->double('balance', 10, 2)->nullable(); 
                $table->string('status')->nullable();;
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
