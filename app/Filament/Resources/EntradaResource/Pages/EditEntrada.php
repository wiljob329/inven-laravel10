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
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
            ->disabled(!auth()->user()->hasRole('super_admin'))
            ->hidden(!auth()->user()->hasRole('super_admin'));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
