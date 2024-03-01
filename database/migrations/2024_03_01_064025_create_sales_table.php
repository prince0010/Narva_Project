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
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('interest_id')->unsigned();
            $table->foreign('interest_id')->references('id')->on('interest')->onUpdate('cascade')->onUpdate('cascade');
            $table->integer('quantity');
            $table->double('total', 5, 2);
            $table->date('sale_date');
            $table->string('remarks');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
