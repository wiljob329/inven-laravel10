<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MaterialResource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class InventoryMaterials extends BaseWidget
{
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(MaterialResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->heading('Inventario de Materiales')
            ->striped()
            ->columns([
                TextColumn::make('descripcion')->searchable()->toggleable(),
                TextColumn::make('unidad_medidas.unidad')->searchable()->toggleable(),
                TextColumn::make('cantidad')->toggleable()->searchable(),
                TextColumn::make('deposito.name')->toggleable()->searchable(),
                TextColumn::make('categoria.name')->searchable()->toggleable(),
                TextColumn::make('alerta')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('activo')->boolean(),
            ]);
    }
}
