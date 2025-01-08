<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MaterialResource;
use App\Models\Material;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
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
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($query) => $query->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                $data['to'],
                                fn ($query) => $query->whereDate('created_at', '<=', $data['to'])
                            );
                    }),
            ])
            ->headerActions([
                Action::make('exportPdf')
                    ->label('Generar PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function ($livewire) {
                        $filters = $livewire->tableFilters['created_at'];
                        $from = $filters['from'];
                        $to = $filters['to'];
                        //$record = MaterialResource::getEloquentQuery()->get();

                        $query = Material::query();
                        if (! empty($from)) {
                            $query->whereDate('created_at', '>=', $from);
                        }
                        if (! empty($to)) {
                            $query->whereDate('created_at', '<=', $to);
                        }

                        $record = $query->get();

                        return response()
                            ->streamDownload(function () use ($record, $from, $to) {
                                echo Pdf::loadHtml(
                                    Blade::render('PDF.inventario', [
                                        'registros' => $record,
                                        'from' => $from,
                                        'to' => $to,
                                        'titulo' => 'Inventario Deposito',
                                    ])
                                )
                                    ->setPaper('A4', 'landscape')
                                    ->download();
                            }, 'Inventario_'.now()->format('YmdHis').'.pdf');
                    }),

            ]);
    }
}
