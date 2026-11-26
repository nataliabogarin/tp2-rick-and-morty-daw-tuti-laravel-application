
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla location
        Schema::create('location', function (Blueprint $table): void {
            $table->integer('id_location')->primary();
            $table->string('name', 150);
            $table->string('type', 100);
            $table->string('dimension', 150);
        });

        // Tabla characters
        Schema::create('characters', function (Blueprint $table): void {
            $table->integer('id_character')->primary();
            $table->string('name', 150);
            $table->string('status', 20);
            $table->string('species', 100);
            $table->string('type', 150);
            $table->string('gender', 20);
            $table->string('image', 255);

            // Claves foráneas de origen y ubicación actual
            $table->integer('id_origin_location')->nullable();
            $table->integer('id_current_location')->nullable();

            $table->foreign('id_origin_location')
                  ->references('id_location')
                  ->on('location')
                  ->nullOnDelete();

           $table->foreign('id_current_location')
                 ->references('id_location')
                 ->on('location')
                 ->nullOnDelete();
        });

        // Tabla episode
        Schema::create('episode', function (Blueprint $table): void {
            $table->integer('id_episode')->primary();
            $table->string('name', 150);
            $table->string('air_date', 50);
            $table->string('episode', 10);
        });

        // Tabla intermedia characters_episode
        Schema::create('characters_episode', function (Blueprint $table): void {
            $table->integer('id_character');
            $table->integer('id_episode');

            $table->primary(['id_character', 'id_episode']);

            $table->foreign('id_character')
                  ->references('id_character')
                  ->on('characters')
                  ->cascadeOnDelete();

           $table->foreign('id_episode')
                  ->references('id_episode')
                  ->on('episode')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters_episode');
        Schema::dropIfExists('episode');
        Schema::dropIfExists('characters');
        Schema::dropIfExists('location');
    }
};
