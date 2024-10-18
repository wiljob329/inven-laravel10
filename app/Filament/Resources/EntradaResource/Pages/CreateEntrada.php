<?php

namespace App\Filament\Resources\EntradaResource\Pages;

use App\Filament\Resources\EntradaResource;
use App\Http\Controllers\CreateEntradaController;
use App\Models\ArticuloEntrada;
use App\Models\Material;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class CreateEntrada extends CreateRecord
{
    protected static string $resource = EntradaResource::class;

    public $materiales = [];

    public function agregarMaterial(): void
    {
        // $this->validate([
        //     'data.material_id' => 'required',
        //     'data.cantidad' => 'required|numeric|min:1',
        // ]);

        $material = [
            'material_id' => $this->data['material_id'],
            'cantidad' => $this->data['cantidad'],
        ];

        $this->materiales[] = $material;

        $this->data['material_id'] = null;
        $this->data['cantidad'] = null;

        // $this->reset('data.material_id', 'data.cantidad');
    }

    #[Computed]
    public function getMaterialesTabla()
    {
        return $this->materiales;
    }

    protected function handleRecordCreation(array $data): Model
    {

        $entrada = static::getModel()::create($data);

        DB::transaction(function () use ($data, $entrada) {
            $entrada->fill($data)->save();

            foreach ($this->materiales as $material) {
                $articulo = new ArticuloEntrada([
                    'entrada_id' => $entrada->id,
                    'material_id' => $material['material_id'],
                    'cantidad' => $material['cantidad'],
                ]);
                $articulo->save();

                $materia = Material::find($material['material_id']);
                $materia->cantidad += $material['cantidad'];
                $materia->save();
            }

        });

        return $entrada;
        // return dd($entrada, $data, $this->materiales);
    }

    // protected function handle(): void
    // {
    //     // Aquí puedes guardar los materiales en la base de datos
    //     // Por ejemplo:
    //     $this->record->articulos()->createMany($this->materiales);
    // }
    // protected function handleRecordCreation(array $data): Model
    // {
    //     $controller = new CreateEntradaController;
    //
    //     return $controller->store(request());
    // }
}
