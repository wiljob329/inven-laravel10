<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Materiales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('descripcion')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->required(),
                                Select::make('unidad_medidas_id')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->label('Unidad de Medidas')
                                    ->relationship('unidad_medidas', 'unidad')
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
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->numeric(),
                                Select::make('depositos_id')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
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
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
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
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->label('Numero minimo permitido')
                                    ->required()
                                    ->numeric(),
                                Toggle::make('activo')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && !auth()->user()->hasRole('super_admin'))
                                    ->onColor('success')
                                    ->offColor('danger'),

                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(['lg' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Codigo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('descripcion')->searchable()->toggleable(),
                TextColumn::make('unidad_medidas.unidad')->searchable()->toggleable(),
                TextColumn::make('cantidad')->toggleable()->searchable(),
                TextColumn::make('deposito.name')->toggleable()->searchable(),
                TextColumn::make('categoria.name')->searchable()->toggleable(),
                TextColumn::make('alerta')->toggleable(),
                IconColumn::make('activo')->boolean(),
            ])
            ->recordClasses(function ($record) {
                return $record->cantidad <= $record->alerta ? 'alerta' : null;
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
