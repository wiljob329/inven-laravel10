<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Deposito;
use App\Models\Material;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                                TextInput::make('descripcion'),
                                TextInput::make('cantidad')->numeric(),
                                Select::make('depositos_id')
                                    ->relationship('deposito', 'name')
                                    ->searchable()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre Deposito')
                                            ->required()
                                    ])
                                    ->createOptionAction(function (Action $action){
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
                                            ->required()
                                    ])
                                    ->createOptionAction(function (Action $action){
                                        return $action
                                            ->modalHeading('Crear Categoria')
                                            ->modalSubmitActionLabel('Crear Categoria')
                                            ->modalWidth('sm');
                                    }),
                                TextInput::make('alerta')
                                    ->label('Numero minimo permitido')
                                    ->numeric(),
                                
                            ])
                            ->columns(3)
                    ])
                    ->columnSpan(['lg' => 2])
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
