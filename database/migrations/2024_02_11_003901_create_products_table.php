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
        if(!Schema::hasTable('products')){
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('prod_type_ID')->unsigned();
                $table->foreign('prod_type_ID')->references('id')->on('prod__types')->onUpdate('cascade')->onDelete('cascade');
                $table->string('part_num');
                $table->string('part_name');
                $table->string('brand');
                $table->string('model');
                $table->string('price_code');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
