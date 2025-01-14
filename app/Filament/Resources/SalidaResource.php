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
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Contracts\HasLabel;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

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
                //
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
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required()
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->closeOnDateSelection()
                            ->displayFormat('d/m/Y'),
                        TextInput::make('destino')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->required(),
                        Select::make('solicitantes_id')
                            ->relationship('solicitante', 'nombre')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                        Select::make('vehiculos_id')
                            ->relationship('vehiculo', 'placa')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->createOptionForm([
                                TextInput::make('tipo')
                                    ->label('Tipo Vehiculo')
                                    ->required(),
                                TextInput::make('placa')
                                    ->label('Placa')
                                    ->required(),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Vehiculo')
                                    ->modalSubmitActionLabel('Crear Vehiculo')
                                    ->modalWidth('sm');
                            }),
                        Select::make('caso')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->native(false)
                            ->live()
                            ->options(Casos::class),
                        Select::make('jefe_id')
                            ->label('Aprobado por')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->native(false)
                            ->live()
                            ->options(Jefe::all()->pluck('nombre', 'id')),
                        TextInput::make('codigo_uxd')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->label('Codigo uno x 10')
                            ->live()
                            ->hidden(fn (Get $get) => $get('caso') != '1x10'),

                    ])->columns(3),
                Section::make('Seleccion de materiales')
                    ->schema([
                        Repeater::make('articulos')
                            ->label('')
                            ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                            ->relationship('articulos')
                            ->schema([
                                Select::make('material_id')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->relationship('material', 'descripcion')
                                    ->searchable()
                                    ->suffix(fn (Get $get) => Material::find($get('material_id'))->unidad_medidas->unidad ?? '')
                                    ->required(),
                                TextInput::make('cantidad')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                TextColumn::make('fecha')->date()->toggleable(),
                TextColumn::make('solicitante.nombre')->searchable(),
                TextColumn::make('destino')->toggleable(),
                TextColumn::make('solicitante.cedula')->label('Cedula Solicitante')->toggleable(),
                TextColumn::make('vehiculo.placa')->toggleable(),
                TextColumn::make('caso')->toggleable(),
                TextColumn::make('codigo_uxd')->toggleable(),

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
                                        Blade::render('PDF/salidapdf', ['record' => $record])
                                    )
                                        ->setPaper('A4', 'landscape')
                                        ->download();
                                }, $record->serial.now()->format('YmdHis').'.pdf');
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
