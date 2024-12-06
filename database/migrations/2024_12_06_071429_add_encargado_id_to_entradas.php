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
        Schema::table('entradas', function (Blueprint $table) {
            //
            $table->dropColumn('recibido_por');
            $table->foreignId('encargado_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            //
            $table->string('recibido_por');
            $table->dropForeign(['encargado_id']);
            $table->dropColumn('encargado_id');
        });
    }
};
