<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntradaResource\Pages;
use App\Models\Cuadrilla;
use App\Models\Deposito;
use App\Models\Entrada;
use App\Models\Material;
use App\Models\UnidadMedidas;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class EntradaResource extends Resource
{
    protected static ?string $model = Entrada::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    protected static ?string $navigationLabel = 'Entrada de Material';

    public static ?string $cantidadMaterial = '';

    public static ?int $unidadMedida = 0;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('codigo_nota_entrega')
                            ->default(fn () => Entrada::getNextCode())
                            ->disabled()
                            ->dehydrated()
                            ->label('Codigo de entrega')
                            ->required(),
                        DatePicker::make('fecha')
                            ->required()
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito')),
                        Select::make('encargado_id')
                            ->label('Encargado')
                            ->options([auth()->user()->id => auth()->user()->name])
                            ->default(auth()->user()->id)
                            ->disabled()
                            ->dehydrated(),
                        Select::make('proveedors_id')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->relationship('proveedor', 'name')
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre del Proveedor')
                                    ->required(),
                                TextInput::make('rif')
                                    ->label('Rif del Proveedor')
                                    ->required(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Proveedor')
                                    ->modalSubmitActionLabel('Crear Proveedor')
                                    ->modalWidth('sm');
                            }),
                        Toggle::make('cuadrilla')
                            ->label('Movimiento de cuadrilla')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->onColor('success')
                            ->offColor('danger')
                            ->live(),
                        Select::make('cuadrilla_id')//falta validar que cuando se escoje una cuadrilla y se pone en off el boton cuadrilla no se envie
                            ->label('Cuadrillas')
                            ->options(Cuadrilla::all()->pluck('nombre', 'id'))
                            ->hidden(fn (Get $get) => $get('cuadrilla') == false),

                    ])->columns(4),

                Section::make('Seleccion de materiales')
                    ->schema([

                        Repeater::make('articulos')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                                            ->modalWidth(MaxWidth::TwoExtraLarge)
                                            ->action(function (array $data, Set $set) {
                                                $material = Material::create([
                                                    'descripcion' => $data['descripcion'],
                                                    'depositos_id' => $data['depositos_id'],
                                                    'unidad_medidas_id' => $data['unidad_medidas_id'],
                                                    'categorias_id' => $data['categorias_id'],
                                                    'alerta' => $data['alerta'],
                                                    'activo' => $data['activo'],
                                                ]);
                                                static::$cantidadMaterial = $data['cantidad'];
                                                static::$unidadMedida = $material->unidad_medidas_id;

                                                $set('material_id', $material->id);

                                                $set('cantidad', static::$cantidadMaterial);
                                                $set('unidad_medidas_id', static::$unidadMedida);

                                            });
                                    })->columnSpan(1),

                                Select::make('unidad_medidas_id')
                                    ->label('Unidad de Medidas')
                                    ->relationship('unidad_medidas', 'unidad')
                                    ->options(UnidadMedidas::all()->pluck('unidad', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        if (! $get('unidad_medidas_id') && $get('material_id')) {
                                            $set('unidad_medidas_id', static::$unidadMedida);
                                        }
                                    })
                                    ->createOptionForm([
                                        TextInput::make('unidad')
                                            ->label('Unidad de Medida')
                                            ->required(),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Crear Unidad de Medida')
                                            ->modalSubmitActionLabel('Crear Unidad')
                                            ->modalWidth('sm');
                                    }),
                                TextInput::make('cantidad')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                            ->columns(3),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo_nota_entrega')->searchable(),
                TextColumn::make('fecha')->date(),
                TextColumn::make('encargado.name'),
                TextColumn::make('proveedor.name'),

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
                        ->action(function (Entrada $record) {
                            return response()
                                ->streamDownload(function () use ($record) {
                                    echo Pdf::loadHtml(
                                        Blade::render('PDF/entradapdf', ['record' => $record])
                                    )
                                        ->setPaper('A4', 'landscape')
                                        ->download();
                                }, $record->codigo_nota_entrega.now()->format('YmdHis').'.pdf');
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
            'index' => Pages\ListEntradas::route('/'),
            'create' => Pages\CreateEntrada::route('/create'),
            'edit' => Pages\EditEntrada::route('/{record}/edit'),
        ];
    }

    public static function getMaterialFormSchema(): array
    {
        return [

            Section::make()
                ->schema([
                    TextInput::make('descripcion')
                        ->label('Descripcion del material')
                        ->required(),
                    Select::make('unidad_medidas_id')
                        ->label('Unidad de Medidas')
                        ->relationship('unidad_medidas', 'unidad')
                        ->options(UnidadMedidas::all()->pluck('unidad', 'id'))
                        ->searchable()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('unidad')
                                ->label('Unidad de Medida')
                                ->required(),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Crear Unidad de Medida')
                                ->modalSubmitActionLabel('Crear Unidad')
                                ->modalWidth('sm');
                        }),
                    TextInput::make('cantidad')
                        ->required()
                        ->numeric()
                        ->minValue(1),

                    Select::make('depositos_id')
                        ->relationship('deposito', 'name')
                        ->options(Deposito::all()->pluck('name', 'id'))
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
                    Toggle::make('activo')
                        ->onColor('success')
                        ->offColor('danger'),

                ])->columns(2),
        ];
    }
}
