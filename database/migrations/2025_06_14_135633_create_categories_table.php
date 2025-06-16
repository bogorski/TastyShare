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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Unikalny identyfikator kategorii
            $table->string('name')->unique(); // Nazwa kategorii (np. "Śniadanie", "Deser")
            $table->string('image')->nullable();
            $table->timestamps(); // Daty utworzenia i ostatniej aktualizacji
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
