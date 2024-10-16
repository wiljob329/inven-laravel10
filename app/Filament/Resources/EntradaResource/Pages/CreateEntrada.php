<?php

namespace App\Filament\Resources\EntradaResource\Pages;

use App\Filament\Resources\EntradaResource;
use App\Http\Controllers\CreateEntradaController;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEntrada extends CreateRecord
{
    protected static string $resource = EntradaResource::class;

    // protected function handleRecordCreation(array $data): Model
    // {
    //     $controller = new CreateEntradaController;
    //
    //     return $controller->store(request());
    // }
}
