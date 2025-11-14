<?php

declare(strict_types=1);

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
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories');
            $table->string('name', 50)->unique();
            $table->string('slug', 52);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_variant_types', function (Blueprint $table): void {
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('variant_type_id')->constrained('variant_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_variant_types');
    }
};
