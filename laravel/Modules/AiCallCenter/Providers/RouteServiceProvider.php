<?php

namespace Modules\AiCallCenter\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'AiCallCenter';

    public function map(): void
    {
        Route::middleware(['web', 'auth', 'verified'])
            ->group(module_path($this->name, '/Routes/web.php'));

        Route::middleware('api')
            ->prefix('api')
            ->name('api.')
            ->group(module_path($this->name, '/Routes/api.php'));
    }
}
