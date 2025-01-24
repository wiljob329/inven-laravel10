<?php

namespace Database\Seeders;

use App\Models\Municipio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Municipio::create(
            [
                'nombre' => 'Municipio Obispo Ramos de Lora',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Miranda',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Pueblo Llano',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Cardenal Quintero',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Justo Briceño',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Rangel',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Santos Marquina',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Caracciolo Parra Olmedo',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Libertador',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Campo Elias',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Aricagua',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Andrés Bello',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Alberto Adriani',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Sucre',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Arzobispo Chacón',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Guaraque',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Rivas Dávila',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Zea',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Tovar',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Antonio Pinto Salinas',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Padre Noguera',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Tulio Febres Cordero',
            ],);
        Municipio::create(
            [
                'nombre' => 'Municipio Julio Cesar Sálas',
            ],);
    }
}
