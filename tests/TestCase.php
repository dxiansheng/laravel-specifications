<?php

namespace Pbmedia\ScoreMatcher\Laravel\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }
    protected function setUpDatabase()
    {
        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        include_once __DIR__ . '/../database/migrations/create_score_matcher_attributes_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_score_matcher_scores_table.php.stub';
        include_once __DIR__ . '/create_products_table.php';

        (new \CreateScoreMatcherAttributesTable)->up();
        (new \CreateScoreMatcherScoresTable)->up();
        (new \CreateProductsTable)->up();
    }
}
