<?php

namespace AnvilM\SPWorlds\Providers;

use AnvilM\SPWorlds\API;
use AnvilM\SPWorlds\Contracts\Services\APIServiceContract;
use AnvilM\SPWorlds\Services\APIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class SPWorldsServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(APIServiceContract::class, APIService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/SPWorlds.php' => config_path('SPWorlds.php'),
        ]);
    }
}
