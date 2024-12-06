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
            $table->foreignId('encargado_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jefe_id')->constrained('jefes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            //
            $table->dropForeign(['encargado_id']);
            $table->dropColumn('encargado_id');
            $table->dropForeign(['jefe_id']);
            $table->dropColumn('jefe_id');
        });
    }
};
