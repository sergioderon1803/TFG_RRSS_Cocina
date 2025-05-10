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
            $table->unsignedBigInteger('autor'); // campo que almacena el id del autor
            $table->foreign('autor')->references('id')->on('users')->onDelete('cascade');
            $table->string('titulo');
            $table->string('imagen')->nullable();
            $table->string('tipo');
            $table->text('ingredientes');
            $table->text('procedimiento');
            $table->tinyInteger('estado'); // 0 = Publico | 1 = Privado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recetas');
    }
};
