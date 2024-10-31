<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalidaResource\Pages;
use App\Models\Salida;
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
                            ->required()
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                        TextInput::make('entregado_a')->required(),
                        TextInput::make('departamento')->required(),
                        TextInput::make('destino')->required(),
                        TextInput::make('cedula')->required(),
                        TextInput::make('vehicle_placa')
                            ->label('Placa del Vehiculo')
                            ->required(),

                    ])->columns(3),
                Section::make('Seleccion de materiales')
                    ->schema([
                        Repeater::make('articulos')
                            ->label('')
                            ->relationship('articulos')
                            ->schema([
                                Select::make('material_id')
                                    ->searchable()
                                    ->relationship('material', 'descripcion')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('cantidad')
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
                Tables\Actions\EditAction::make(),
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
