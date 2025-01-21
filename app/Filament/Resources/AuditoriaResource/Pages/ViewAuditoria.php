<?php

namespace App\Filament\Resources\AuditoriaResource\Pages;

use App\Filament\Resources\AuditoriaResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class ViewAuditoria extends ViewRecord
{
    protected static string $resource = AuditoriaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Cambio')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Usuario'),
                        TextEntry::make('action')
                            ->label('Acción')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                            }),
                        TextEntry::make('model_type')
                            ->label('Modelo'),
                        TextEntry::make('model_id')
                            ->label('ID del Registro'),
                        TextEntry::make('description')
                            ->label('Descripción'),
                        TextEntry::make('created_at')
                            ->label('Fecha y Hora')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2),

                Section::make('Valores')
                    ->schema([
                        TextEntry::make('old_values')
                            ->label('Valores Anteriores')
                            ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'No hay valores anteriores')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('new_values')
                            ->label('Nuevos Valores')
                            ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'No hay nuevos valores')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
