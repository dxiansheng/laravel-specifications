<?php

namespace Pbmedia\Specifications\Laravel;

use Illuminate\Support\ServiceProvider;
use Pbmedia\Specifications\Matcher;

class SpecificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $timestamp = date('Y_m_d_His', time());

        if (!class_exists('CreateSpecificationsAttributesTable')) {
            $this->publishes([
                __DIR__ . '/../resources/migrations/create_specifications_attributes_table.php.stub' => database_path('migrations/' . $timestamp . '_create_specifications_attributes_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateSpecificationsScoresTable')) {
            $this->publishes([
                __DIR__ . '/../resources/migrations/create_specifications_scores_table.php.stub' => database_path('migrations/' . $timestamp . '_create_specifications_scores_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('laravel-specifications-matcher', function ($app) {
            return $app->make(Matcher::class);
        });
    }
}
