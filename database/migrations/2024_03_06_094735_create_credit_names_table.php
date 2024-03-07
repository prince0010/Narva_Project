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
            $table->integer('credit_info_ID')->unsigned();
            $table->foreign('credit_info_ID')->references('id')->on('credit_info')->onUpdate('CASCADE')->onDelete('CASCADE');
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