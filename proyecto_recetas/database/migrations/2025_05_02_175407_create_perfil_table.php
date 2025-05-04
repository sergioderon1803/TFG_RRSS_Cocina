<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('perfil', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->foreign('email')->references('email')->on('usuarios')->onDelete('cascade');
            $table->string('img_perfil')->nullable();
            $table->string('img_banner')->nullable();
            $table->string('biografia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('perfil');
    }
};
