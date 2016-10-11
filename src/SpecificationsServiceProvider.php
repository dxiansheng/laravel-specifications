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
        if (!class_exists('CreateSpecificationsAttributesTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../database/migrations/create_specifications_attributes_table.php.stub' => database_path('migrations/' . $timestamp . '_create_specifications_attributes_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateSpecificationsScoresTable')) {
            $timestamp = date('Y_m_d_His', time() + 1);

            $this->publishes([
                __DIR__ . '/../database/migrations/create_specifications_scores_table.php.stub' => database_path('migrations/' . $timestamp . '_create_specifications_scores_table.php'),
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
