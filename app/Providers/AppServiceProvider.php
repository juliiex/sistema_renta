<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Importa View correctamente
use App\Models\Edificio;

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
public function boot()
{
    View::composer(['apartamento.form', 'apartamento.index', 'apartamento.create', 'apartamento.edit'], function ($view) {
        $view->with('edificios', Edificio::all());
    });
}

}
