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
        if(!Schema::hasTable('interest')){ 
        Schema::create('interest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('interest_name');
            $table->float('interest_rate', 5, 2);
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
        Schema::dropIfExists('interest');
    }
};
