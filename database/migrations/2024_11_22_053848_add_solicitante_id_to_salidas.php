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
            $table->foreignId('solicitante_id')->constrained('solicitante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            //
            $table->dropForeign(['solicitante_id']);
            $table->dropColumn('solicitante_id');
        });
    }
};
