<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntradaResource\Pages;
use App\Models\Entrada;
use App\Models\Material;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntradaResource extends Resource
{
    protected static ?string $model = Entrada::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    protected static ?string $navigationLabel = 'Entrada de Material';

    public static string $cantidadMaterial = '';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                // Group::make()
                //     ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('codigo_nota_entrega')
                            ->label('Codigo de entrega')
                            ->autofocus()
                            ->unique()
                            ->validationMessages([
                                'unique' => 'El codigo ya existe',
                            ])
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
                                    ->required(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Proveedor')
                                    ->modalSubmitActionLabel('Crear Proveedor')
                                    ->modalWidth('sm');
                            }),

                    ])->columns(4),

                // ])->columns('full'),
                // Group::make()
                //     ->schema([
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
                                    ->createOptionForm(static::getMaterialFormSchema())
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Crear Material')
                                            ->modalSubmitActionLabel('Crear Material')
                                            ->modalWidth('sm')
                                            ->action(function (array $data, Set $set) {
                                                $material = Material::create([
                                                    'descripcion' => $data['descripcion'],
                                                    'depositos_id' => $data['depositos_id'],
                                                    'categorias_id' => $data['categorias_id'],
                                                    'alerta' => $data['alerta'],
                                                ]);
                                                static::$cantidadMaterial = $data['cantidad'];

                                                $set('material_id', $material->id);

                                                $set('cantidad', static::$cantidadMaterial);

                                            });
                                    })->columnSpan(1),

                                TextInput::make('cantidad')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        if (! $get('cantidad') && $get('material_id')) {
                                            $set('cantidad', static::$cantidadMaterial);
                                        }
                                    })->columnSpan(1),
                            ])
                            ->addActionLabel('Agregar otro material')
                            ->live()
                            ->columns(2),
                    ]),
                // ]),

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

    public static function getMaterialFormSchema(): array
    {
        return [

            TextInput::make('descripcion')
                ->label('Descripcion del material')
                ->required(),
            TextInput::make('cantidad')
                ->required()
                ->numeric()
                ->minValue(1),

            Select::make('depositos_id')
                ->relationship('deposito', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre Deposito')
                        ->required(),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading('Crear Deposito')
                        ->modalSubmitActionLabel('Crear Deposito')
                        ->modalWidth('sm');
                }),
            Select::make('categorias_id')
                ->relationship('categoria', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre Categoria')
                        ->required(),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading('Crear Categoria')
                        ->modalSubmitActionLabel('Crear Categoria')
                        ->modalWidth('sm');
                }),
            TextInput::make('alerta')
                ->label('Numero minimo permitido')
                ->numeric(),
        ];
    }
}
