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

        if(!Schema::hasTable('prod_types')){
            Schema::create('prod_types', function (Blueprint $table) {
                $table->increments('id');
                $table->string('product_type_name')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Schema::table('prod__types', function (Blueprint $table) {
                //     $table->softDeletes();
                // });
            });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_types');
    }
};
