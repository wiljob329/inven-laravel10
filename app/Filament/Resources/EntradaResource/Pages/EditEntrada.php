<?php

namespace App\Filament\Resources\EntradaResource\Pages;

use App\Filament\Resources\EntradaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntrada extends EditRecord
{
    public $materiales = [];

    protected static string $resource = EntradaResource::class;

    public function getMaterialesTabla()
    {
        return $this->materiales;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
