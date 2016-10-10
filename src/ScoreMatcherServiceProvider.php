<?php

namespace Pbmedia\ScoreMatcher\Laravel;

use Illuminate\Support\ServiceProvider;
use Pbmedia\ScoreMatcher\Matcher;

class ScoreMatcherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $timestamp = date('Y_m_d_His', time());

        if (!class_exists('CreateScoreMatcherAttributesTable')) {
            $this->publishes([
                __DIR__ . '/../resources/migrations/create_score_matcher_attributes_table.php.stub' => database_path('migrations/' . $timestamp . '_create_score_matcher_attributes_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateScoreMatcherScoresTable')) {
            $this->publishes([
                __DIR__ . '/../resources/migrations/create_score_matcher_scores_table.php.stub' => database_path('migrations/' . $timestamp . '_create_score_matcher_scores_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('laravel-score-matcher', function ($app) {
            return $app->make(Matcher::class);
        });
    }
}
