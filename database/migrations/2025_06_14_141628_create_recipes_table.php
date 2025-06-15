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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id(); // Unikalny identyfikator
            $table->string('title'); // Tytuł przepisu
            $table->text('description'); // Opis przepisu
            $table->text('ingredients'); // Składniki
            $table->text('instructions'); // Instrukcje wykonania
            $table->integer('preparation_time'); // Czas przygotowania w minutach
            $table->string('image')->nullable(); // Ścieżka do zdjęcia (może być null)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID użytkownika (klucz obcy)
            $table->timestamps(); // Daty utworzenia i ostatniej aktualizacji
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
