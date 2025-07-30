<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('conversion_rate');
            $table->float('multiplier');
            $table->boolean('is_base')->default(false);
            $table->boolean('is_max')->default(false);
            $table->timestamps();

            $table->index('product_id');
            $table->index('is_base');
            $table->index('is_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
