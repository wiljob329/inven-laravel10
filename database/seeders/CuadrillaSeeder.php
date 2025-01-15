<?php

namespace Database\Seeders;

use App\Models\Cuadrilla;
use Illuminate\Database\Seeder;

class CuadrillaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Cuadrilla::create(
            [
                'nombre' => 'C1',
            ]);
        Cuadrilla::create(
            [
                'nombre' => 'C2',
            ]);
        Cuadrilla::create(
            [
                'nombre' => 'C3',
            ]);
    }
}
