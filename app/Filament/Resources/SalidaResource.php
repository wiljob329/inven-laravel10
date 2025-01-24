<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalidaResource\Pages;
use App\Models\Jefe;
use App\Models\Material;
use App\Models\Salida;
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
use Filament\Resources\Resource;
use Filament\Support\Contracts\HasLabel;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Random\Engine\Secure;

enum Casos: string implements HasLabel
{
    case Eventual = 'eventual';
    case Unox10 = '1x10';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

class SalidaResource extends Resource
{
    protected static ?string $model = Salida::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    protected static ?string $navigationLabel = 'Salida de Material';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                        Toggle::make('es_cuadrilla')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->label('¿Movimiento de Cuadrilla?')
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false)
                            ->live(),
                        Select::make('solicitantes_id')
                            ->relationship('solicitante', 'nombre')
                            ->searchable()
                            ->hidden(fn (Get $get) => $get('es_cuadrilla'))
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required(),
                                TextInput::make('cargo')
                                    ->label('Cargo')
                                    ->required(),
                                TextInput::make('gerencia')
                                    ->label('Gerencia')
                                    ->required(),
                                TextInput::make('cedula')
                                    ->label('Cedula')
                                    ->required(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Solicitante')
                                    ->modalSubmitActionLabel('Crear Solicitante')
                                    ->modalWidth('sm');
                            }),
                        Select::make('cuadrilla_id')
                            ->relationship('cuadrilla', 'nombre')
                            ->native(false)
                            ->required()
                            ->visible(fn (Get $get) => $get('es_cuadrilla'))
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin')),
                ])->columns(2),
                Section::make()
                    ->schema([
                        TextInput::make('serial')
                            ->default(fn () => Salida::getNextCode())
                            ->disabled()
                            ->dehydrated()
                            ->label('Serial Salida')
                            ->required(),
                        Select::make('encargado_id')
                            ->label('Encargado')
                            ->options([auth()->user()->id => auth()->user()->name])
                            ->default(auth()->user()->id)
                            ->disabled()
                            ->dehydrated(),
                        DatePicker::make('fecha')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->required()
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                        TextInput::make('destino')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->required(),
                        Select::make('municipio_id')
                            ->relationship('municipio', 'nombre')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin')),
                        Select::make('vehiculos_id')
                            ->relationship('vehiculo', 'placa')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->createOptionForm([
                                TextInput::make('placa')
                                    ->label('Placa')
                                    ->required(),
                                TextInput::make('modelo')
                                    ->label('Modelo')
                                    ->required(),
                                TextInput::make('descripcion')
                                    ->label('Descripcion')
                                    ->required(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Vehiculo')
                                    ->modalSubmitActionLabel('Crear Vehiculo')
                                    ->modalWidth('sm');
                            }),
                        Select::make('caso')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->native(false)
                            ->live()
                            ->options(Casos::class),
                        Select::make('jefe_id')
                            ->label('Aprobado por')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->native(false)
                            ->live()
                            ->options(Jefe::all()->pluck('nombre', 'id')),
                        TextInput::make('codigo_uxd')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->label('Codigo uno x 10')
                            ->live()
                            ->hidden(fn (Get $get) => $get('caso') != '1x10'),

                    ])->columns(2),
                Section::make('Seleccion de materiales')
                    ->schema([
                        Repeater::make('articulos')
                            ->label('')
                            ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                            ->relationship('articulos')
                            ->schema([
                                Select::make('material_id')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->relationship('material', 'descripcion')
                                    ->searchable()
                                    ->suffix(fn (Get $get) => Material::find($get('material_id'))->unidad_medidas->unidad ?? '')
                                    ->required(),
                                TextInput::make('cantidad')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
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
                TextColumn::make('serial'),
                TextColumn::make('fecha')
                    ->searchable()
                    ->date('d/m/Y')
                    ->toggleable(),
                TextColumn::make('solicitante.nombre')
                    ->searchable()
                    ->label('Solicitante'),
                TextColumn::make('destino')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('municipio.nombre')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('solicitante.cedula')
                    ->label('Cedula Solicitante')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cuadrilla.nombre')
                    ->label('Cuadrilla')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('vehiculo.placa')
                    ->toggleable(),
                TextColumn::make('caso')
                    ->toggleable(),
                TextColumn::make('codigo_uxd')
                    ->label('Codigo 1X10')
                    ->searchable()
                    ->toggleable(),
            ])
            ->defaultSort('fecha', 'desc')
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
                                        Blade::render('PDF/salidapdf', ['record' => $record])
                                    )
                                        ->setPaper('A4', 'landscape')
                                        ->download();
                                }, 'SA-'.now()->format('YmdHis').'.pdf');
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
