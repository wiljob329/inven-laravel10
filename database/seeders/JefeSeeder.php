<?php

namespace Database\Seeders;

use App\Models\Jefe;
use Illuminate\Database\Seeder;

class JefeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Jefe::create(
            [
                'nombre' => 'Leida Rosa Marquez',
                'cargo' => 'Presidente(E)',
                'cedula' => '7097876',
            ],
            [
                'nombre' => 'Jeanmark Gabriel Marquez Buelvas',
                'cargo' => 'Gerente General(P) Ingenieria y Operaciones',
                'cedula' => '24583208',
            ],
            [
                'nombre' => '',
                'cargo' => '',
                'cedula' => '',
            ]
        );
    }
}
