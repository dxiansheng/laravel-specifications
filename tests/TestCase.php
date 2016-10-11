<?php

namespace Pbmedia\Specifications\Laravel\Tests;

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

        include_once __DIR__ . '/../database/migrations/create_specifications_attributes_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_specifications_scores_table.php.stub';
        include_once __DIR__ . '/create_products_table.php';

        (new \CreateSpecificationsAttributesTable)->up();
        (new \CreateSpecificationsScoresTable)->up();
        (new \CreateProductsTable)->up();
    }
}
