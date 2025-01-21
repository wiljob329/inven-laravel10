<?php

namespace App\Filament\Resources\EntradaResource\Pages;

use App\Filament\Resources\EntradaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntrada extends EditRecord
{
    protected static string $resource = EntradaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    // public function mount(int|string $record): void
    // {
    //     parent::mount($record);
    //     dd($this->record->toArray());
    //     //
    //     // dd($this->record->cuadrilla_id);
    //     // dd($this->form->getState()['cuadrilla_id']);
    // }
}
