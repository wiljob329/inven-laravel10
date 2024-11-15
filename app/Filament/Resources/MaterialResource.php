<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Inventario de Material';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('descripcion')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->required(),
                                TextInput::make('cantidad')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->numeric(),
                                Select::make('depositos_id')
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
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
                                    ->disabled(fn (string $operation) => $operation == 'edit' && auth()->user()->hasRole('deposito'))
                                    ->label('Numero minimo permitido')
                                    ->required()
                                    ->numeric(),

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
                TextColumn::make('descripcion')->searchable()->toggleable(),
                TextColumn::make('cantidad'),
                TextColumn::make('deposito.name'),
                TextColumn::make('categoria.name'),
                TextColumn::make('alerta'),
            ])
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
