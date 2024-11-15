<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalidaResource\Pages;
use App\Models\Salida;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class SalidaResource extends Resource
{
    protected static ?string $model = Salida::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    protected static ?string $navigationLabel = 'Salida de Material';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        DatePicker::make('fecha')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required()
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                        TextInput::make('entregado_a')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required(),
                        TextInput::make('departamento')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required(),
                        TextInput::make('destino')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required(),
                        TextInput::make('cedula')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required(),
                        TextInput::make('vehicle_placa')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->label('Placa del Vehiculo')
                            ->required(),

                    ])->columns(3),
                Section::make('Seleccion de materiales')
                    ->schema([
                        Repeater::make('articulos')
                            ->label('')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->relationship('articulos')
                            ->schema([
                                Select::make('material_id')
                                    ->searchable()
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->relationship('material', 'descripcion')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('cantidad')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->columnSpan(1),
                            ])
                            ->addActionLabel('Agregar otro material')
                            ->live()
                            ->columns(2),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')->date(),
                TextColumn::make('entregado_a')->searchable(),
                TextColumn::make('departamento'),
                TextColumn::make('destino'),
                TextColumn::make('cedula'),
                TextColumn::make('vehicle_placa'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Salida $record) {
                            return response()
                                ->streamDownload(function () use ($record) {
                                    echo Pdf::loadHtml(
                                        Blade::render('pdf', ['record' => $record])
                                    )
                                        ->setPaper('letter', 'landscape')
                                        ->download();
                                }, $record->number.'.pdf');
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalidas::route('/'),
            'create' => Pages\CreateSalida::route('/create'),
            'edit' => Pages\EditSalida::route('/{record}/edit'),
        ];
    }
}
