<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('autor'); // campo que almacena el email del autor
            $table->foreign('autor')->references('email')->on('users')->onDelete('cascade');
            $table->string('titulo');
            $table->string('imagen')->nullable();
            $table->string('tipo');
            $table->string('ingredientes');
            $table->string('procedimiento');
            $table->timestamp('f_creacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recetas');
    }
};
