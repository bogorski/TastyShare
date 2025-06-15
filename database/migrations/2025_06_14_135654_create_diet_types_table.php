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
        Schema::create('diet_types', function (Blueprint $table) {
            $table->id(); // Unikalny identyfikator typu diety
            $table->string('name')->unique(); // Nazwa typu diety (np. "Wegetariańska", "Bezglutenowa")
            $table->timestamps(); // Daty utworzenia i ostatniej aktualizacji
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_types');
    }
};
