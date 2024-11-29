<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Illuminate\Http\Request;

class CreateEntradaController extends Controller
{
    //
    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'articulos' => 'required|array',
        //     'articulos.*.material_id' => 'required',
        //     'articulos.*.cantidad' => 'required',
        // ]);
        //
        // $count = count($request->input('articulos', []));

        // return dd($data);
    }

    public function pdf(Request $request)
    {
        $record = Salida::all()->first();

        return view('test', compact('record'));

    }
}
