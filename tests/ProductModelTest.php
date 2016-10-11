<?php

namespace Pbmedia\Specifications\Laravel\Tests;

class ProductModelTest extends TestCase
{
    public function testProductModel()
    {
        $product = ProductModel::create([
            'name' => 'MacBook',
        ]);

        $this->seeInDatabase('products', [
            'id'   => 1,
            'name' => 'MacBook',
        ]);
    }
}
