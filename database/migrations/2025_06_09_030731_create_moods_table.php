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
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['senang', 'biasa', 'capek', 'stres']); // 😄, 😐, 😫, 😠
            $table->text('notes')->nullable(); // Alasan mood opsional
            $table->date('log_date'); // Tanggal mood dicatat
            $table->timestamps();

            $table->unique(['user_id', 'log_date']); // Satu user hanya bisa input satu mood per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
