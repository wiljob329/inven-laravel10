<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntradaResource\Pages;
use App\Filament\Resources\EntradaResource\RelationManagers;
use App\Models\Entrada;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntradaResource extends Resource
{
    protected static ?string $model = Entrada::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationLabel = 'Entrada de Material';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                    TextInput::make('codigo_nota_entrega')
                                        ->label('Codigo de entrega')
                                        ->required(),
                                    DatePicker::make('fecha')
                                        ->required()
                                        ->native(false)
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->closeOnDateSelection()
                                        ->displayFormat('d/m/Y'),
                                    TextInput::make('recibido_por')->required(),
                                    Select::make('proveedors_id')
                                        ->relationship('proveedor', 'name')
                                        ->searchable()
                                        ->required()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Nombre del Proveedor')
                                                ->required()
                                        ])
                                        ->createOptionAction(function (Action $action){
                                            return $action
                                                ->modalHeading('Crear Proveedor')
                                                ->modalSubmitActionLabel('Crear Proveedor')
                                                ->modalWidth('sm');
                                        }),

                            ])->columns(2),
                        ]),
                Group::make()
                    ->schema([
                        Section::make('Seleccion de materiales')
                            ->schema([

                                    Repeater::make('articulos')
                                        ->label('Materiales')
                                        ->relationship('articulos')
                                        ->schema([
                                           Select::make('material_id') 
                                            ->searchable()
                                            ->relationship('material', 'descripcion')
                                            ->required(),
                                           TextInput::make('cantidad')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->columns(1)
                                        //    Select::make('material_id')
                                        //     ->searchable()
                                        //     ->relationship('material', 'deposito')
                                        //     ->required()
                                        //     ->columnSpan(1)
                                        ])
                                        ->addActionLabel('Agregar otro material')
                                                ])
                            ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo_nota_entrega')->searchable(),
                TextColumn::make('fecha')->date(),
                TextColumn::make('recibido_por'),
                TextColumn::make('proveedor.name'),
                
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
            'index' => Pages\ListEntradas::route('/'),
            'create' => Pages\CreateEntrada::route('/create'),
            'edit' => Pages\EditEntrada::route('/{record}/edit'),
        ];
    }
}
