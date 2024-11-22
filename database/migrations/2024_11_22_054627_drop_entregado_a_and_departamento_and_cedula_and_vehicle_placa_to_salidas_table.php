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
        Schema::table('salidas', function (Blueprint $table) {
            //
            $table->dropColumn(['entregado_a', 'departamento', 'cedula', 'vehicle_placa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            //
            $table->string('entregado_a');
            $table->string('departamento');
            $table->string('cedula');
            $table->string('vehicle_placa');
        });
    }
};
