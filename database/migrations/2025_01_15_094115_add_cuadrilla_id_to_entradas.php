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
            //
            $table->foreignId('cuadrilla_id')->nullable()->constrained('cuadrillas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            //
            $table->dropForeign(['cuadrilla_id']);
            $table->dropColumn('cuadrilla_id');
        });
    }
};
