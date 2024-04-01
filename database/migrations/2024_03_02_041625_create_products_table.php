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
                $table->integer('prod_type_ID')->unsigned()->nullable();
                $table->foreign('prod_type_ID')->references('id')->on('prod_types')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('supplier_ID')->unsigned()->nullable();
                $table->foreign('supplier_ID')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
                $table->string('part_num')->nullable();
                $table->string('part_name')->nullable();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('price_code')->nullable();
                $table->decimal('markup', 8, 2)->nullable(); //Percentage
                $table->decimal('counter_price', 8, 2)->nullable(); //The Output for the Price_Code * (percentage + 1)
                $table->integer('stock')->nullable();
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
        Schema::dropIfExists('products');
    }
};
