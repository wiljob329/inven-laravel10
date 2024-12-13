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
            ]);
        Jefe::create(
            [
                'nombre' => 'Jeanmark Gabriel Marquez Buelvas',
                'cargo' => 'Gerente General(P) Ingenieria y Operaciones',
                'cedula' => '24583208',
            ]);
        Jefe::create(
            [
                'nombre' => 'Elil Karina Marquez Paredes',
                'cargo' => 'Jefe de Division',
                'cedula' => '15516012',
            ]);
    }
}
