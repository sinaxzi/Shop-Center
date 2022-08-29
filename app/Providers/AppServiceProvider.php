<?php

namespace App\Providers;

use App\Models\CustomSanctum;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);

            DB::getDoctrineSchemaManager()
                ->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'string');
            DB::getDoctrineSchemaManager()
                ->getDatabasePlatform()->registerDoctrineTypeMapping('polygon', 'string');
        }

        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(CustomSanctum::class);
    }
}
