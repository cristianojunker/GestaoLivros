<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
     * Define a prevenção de lazy loading para evitar consultas N+1 durante o desenvolvimento.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            throw new \RuntimeException(
                "Lazy loading detectado em " . $model::class . " para a relação [{$relation}]."
            );
        });
    }
}
