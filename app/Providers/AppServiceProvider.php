<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // La vista de paginación que trae Laravel usa Tailwind y nosotros no lo usamos,
        // así que le indicamos que use nuestra propia vista (en español)
        Paginator::defaultView('partials.paginacion');
    }
}
