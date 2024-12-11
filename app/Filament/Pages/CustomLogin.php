<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends Login
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';
    //
    // protected static string $view = 'filament.pages.custom-login';
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (app()->environment('local')) {
            $this->form->fill([
                'name' => 'administrador',
                'password' => 'administrador123',
            ]);
        }
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['name'],
            'password' => $data['password'],
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Login');
    }

    public function getHeading(): string|Htmlable
    {
        return __('');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Usuario')
            ->required()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->autocomplete();
    }
}
