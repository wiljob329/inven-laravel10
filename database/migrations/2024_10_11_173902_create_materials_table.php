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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->foreignId('depositos_id')->constrained('depositos')->cascadeOnDelete();
            $table->foreignId('categorias_id')->constrained('categorias')->cascadeOnDelete();
            $table->integer('alerta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
