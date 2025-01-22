<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->boolean('es_cuadrilla')->default(false)->after('destino');
        });
    }

    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->dropColumn('es_cuadrilla');
        });
    }
};
