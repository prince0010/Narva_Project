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
        if(!Schema::hasTable('supplies')){
            Schema::create('supplies', function (Blueprint $table) {
                $table->increments('id'); // Foreign Key from the Supplier ID Number in Supplier Table Data
                $table->integer('supplier_num')->unsigned();
                // $table->foreign('supplier_num')->references('id')->on('suppliers')->onDelete('cascade');
                $table->foreign('supplier_num')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');  // Supplier Number(id)Foreignkey from supplierController suppler_name(id)PrimaryKey
                // $table->foreign('supplier_num')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('set null'); // if you dont want to delete as well the inventory record of the supplier data
                $table->string('part_num');
                $table->string('brand');
                $table->string('model');
                $table->string('code');
                $table->integer('quantity');
                $table->timestamps();
            });
        }
        
    }

   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('supplies');
    }
};
