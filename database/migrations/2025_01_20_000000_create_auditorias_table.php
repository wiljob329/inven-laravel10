<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');  // 'created', 'updated', 'deleted'
            $table->string('model_type');  // nombre del modelo
            $table->unsignedBigInteger('model_id');  // ID del registro
            $table->json('old_values')->nullable();  // valores anteriores
            $table->json('new_values')->nullable();  // nuevos valores
            $table->text('description')->nullable();  // descripción del cambio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
