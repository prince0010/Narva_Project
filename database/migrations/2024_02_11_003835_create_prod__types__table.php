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
        Schema::create('prod__types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_type_name');
            $table->timestamps();
            $table->softDeletes();
            
            // Schema::table('prod__types', function (Blueprint $table) {
            //     $table->softDeletes();
            // });
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('prod__types', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
        Schema::dropIfExists('prod__types');
    }
};
