<?php

namespace Modules\AiCallCenter\Providers;

use Illuminate\Support\ServiceProvider;

class AiCallCenterServiceProvider extends ServiceProvider
{
    protected string $name = 'AiCallCenter';
    protected string $nameLower = 'aicallcenter';

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $path = module_path($this->name, 'Config/config.php');
        $this->publishes([$path => config_path($this->nameLower . '.php')], 'config');
        $this->mergeConfigFrom($path, $this->nameLower);
    }

    protected function registerViews(): void
    {
        $sourcePath = module_path($this->name, 'Resources/views');
        $this->loadViewsFrom($sourcePath, $this->nameLower);
    }
}
