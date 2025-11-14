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
        Schema::create('variant_type_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('variant_type_id')->constrained('variant_types');
            $table->string('name', 50);
            $table->string('slug', 103);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['variant_type_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_type_values');
    }
};
