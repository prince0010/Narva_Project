
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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id(); // Foreign Key from the Supplier ID Number in Supplier Table Data
            $table->integer('supplier_num');  // Supplier Number(id)Foreignkey from supplierController suppler_name(id)PrimaryKey
            $table->string('part_num');
            $table->string('brand');
            $table->string('model');
            $table->string('code');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};