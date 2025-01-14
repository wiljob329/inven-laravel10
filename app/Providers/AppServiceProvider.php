<?php

namespace App\Providers;

use App\Models\ArticuloEntrada;
use App\Models\ArticuloSalida;
use App\Observers\ArticuloEntradaObserver;
use App\Observers\ArticuloSalidaObserver;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        ArticuloEntrada::observe(ArticuloEntradaObserver::class);
        ArticuloSalida::observe(ArticuloSalidaObserver::class);
        FilamentAsset::register([
            Css::make('app', public_path('/css/app')),
        ]);
    }
}
