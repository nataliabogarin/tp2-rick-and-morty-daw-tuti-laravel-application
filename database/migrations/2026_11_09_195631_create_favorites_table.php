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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Esto crea favorable_id y favorable_type automáticamente
            $table->morphs('favorable');

            $table->timestamps();
            
            // Evita que un usuario guarde el mismo elemento dos veces
            $table->unique(['user_id', 'favorable_id', 'favorable_type']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
