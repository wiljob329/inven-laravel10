<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MaterialResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;

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
            ->recordUrl(fn (Model $record) => route('filament.dashboard.resources.materials.edit', $record))
            ->striped()
            ->columns([
                TextColumn::make('descripcion')->searchable()->toggleable(),
                TextColumn::make('unidad_medidas.unidad')->searchable()->toggleable(),
                TextColumn::make('cantidad')->toggleable()->searchable(),
                TextColumn::make('deposito.name')->toggleable()->searchable(),
                TextColumn::make('categoria.name')->searchable()->toggleable(),
                TextColumn::make('alerta')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('Desde'),
                        DatePicker::make('to')
                            ->label('Hasta')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->headerActions([
                Action::make('exportPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function () {
                        $record = MaterialResource::getEloquentQuery()->get();

                        return response()
                            ->streamDownload(function () use ($record) {
                                echo Pdf::loadHtml(
                                    Blade::render('PDF.inventario', ['registros' => $record, 'titulo' => 'Inventario Deposito Libertador'])
                                )
                                    ->setPaper('A4', 'landscape')
                                    ->download();
                            }, 'Inventario_'.now()->format('YmdHis').'.pdf');
                    }),

            ]);
    }
}
